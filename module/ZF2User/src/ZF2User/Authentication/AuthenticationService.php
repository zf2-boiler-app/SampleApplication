<?php
namespace ZF2User\Authentication;
class AuthenticationService extends \Zend\Authentication\AuthenticationService{
	const AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED = -5;
	const AUTH_RESULT_HYBRID_AUTH_CANCELED = -4;
	const AUTH_RESULT_HYBRID_AUTH_UNAVAILABLE = -3;
	const AUTH_RESULT_UNREGISTERED_USER = -2;
	const AUTH_RESULT_USER_STATE_PENDING = -1;
	const AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG = 0;
	const AUTH_RESULT_VALID = 1;

	const AUTH_SERVICE_LOCAL = 'local';
	const AUTH_SERVICE_FACEBOOK = 'facebook';
	const AUTH_SERVICE_GOOGLE = 'google';
	const AUTH_SERVICE_TWITTER = 'twitter';

	/**
	 * @var \ZF2User\Service\UserService
	 */
	private $userService;

    /**
     * Constructor
     * @param \Zend\Authentication\Storage\StorageInterface $oStorage
     * @param \Zend\Authentication\Adapter\AdapterInterface $oAdapter
     */
    public function __construct(\Zend\Authentication\Storage\StorageInterface $oStorage, \Zend\Authentication\Adapter\AdapterInterface $oAdapter){
        $this->setStorage($oStorage)->setAdapter($oAdapter);
    }

    /**
     * @param \ZF2User\Service\UserService $oUserService
     * @return \ZF2User\Authentication\AuthenticationService
     */
    public function setUserService(\ZF2User\Service\UserService $oUserService){
    	$this->userService = $oUserService;
    	return $this;
    }

    public function login($sIdentity,$sCredential, $sService = self::AUTH_SERVICE_LOCAL){
        if(!self::authServiceExists($sService))throw new \Exception('authentication service doesn\'t exist : '.$sService);
        if($sService === self::AUTH_SERVICE_LOCAL){
    		if(!is_string($sIdentity) || !is_string($sCredential))throw new \Exception('Identity ('.gettype($sIdentity).') and/or credential('.gettype($sCredential).') are not strings');
	        $oAuthResult = $this->getAdapter()->setIdentity($sIdentity)
	        ->setCredential($sCredential)
	        ->authenticate();
	        if($oAuthResult->isValid()){
	        	//Check user's state
	        	$aUserStateInfos = $this->getAdapter()->getResultRowObject('user_id','user_state');
	        	$iUserId = $aUserStateInfos->user_id;
	        	$sUserState = $aUserStateInfos->user_state;
	        }
	        else switch($oAuthResult->getCode()){
	        	case \Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
	        	case \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS:
	        	case \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID:
	        		return self::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG;
	        	case \Zend\Authentication\Result::FAILURE_UNCATEGORIZED:
	        	default:
	        		throw new \Exception('Unknown result failure code : '.$oAuthResult->getCode());
	        }
        }
        elseif($this->userService){
        	$oHybridAuthAdapter = $this->userService->getServiceLocator()->get('HybridAuthAdapter');
        	if($oHybridAuthAdapter instanceof \Exception)return self::AUTH_RESULT_HYBRID_AUTH_UNAVAILABLE;

        	//Clear providers storage
        	$oHybridAuthAdapter->logoutAllProviders();
        	try{
        		$oHybridProvider = $oHybridAuthAdapter->authenticate($sService);
        		$oUserProfile = $oHybridProvider->getUserProfile();
        	}
        	catch(\Exception $oException){
        		$oHybridProvider->logout();
        		switch($oException->getCode()){
        			case 5 : return self::AUTH_RESULT_HYBRID_AUTH_CANCELED;
        			case 6 :
        			case 7 : return self::AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED;
        			default:
        				throw new \Exception('Unexpected hybrid auth excecption return code : '.$oException->getCode());
        		}
        	}

        	//Try to register user
        	if(($oUser = $this->userService->getUserFromProvider($oUserProfile,$sService)) instanceof \ZF2User\Entity\UserEntity){
        		$iUserId = $oUser->getUserId();
        		$sUserState = $oUser->getUserState();
        	}
        	else return self::AUTH_RESULT_UNREGISTERED_USER;
        }
        else throw new \Exception('User service is undefined');

        //Authentication is valid, check user state
        if(!isset($iUserId,$sUserState))throw new \Exception('User\'s id or user\'s state are undefined');

        if($sUserState === \ZF2User\Model\UserModel::USER_STATUS_ACTIVE){
        	//Store user id
        	$this->getStorage()->write($iUserId);
        	return self::AUTH_RESULT_VALID;
        }
        else return self::AUTH_RESULT_USER_STATE_PENDING;
    }

    /**
     * Check if authentication service exists
     * @param string $sService
     * @return boolean
     */
	private static function authServiceExists($sService){
		switch($sService){
			case self::AUTH_SERVICE_LOCAL:
			case self::AUTH_SERVICE_FACEBOOK:
			case self::AUTH_SERVICE_GOOGLE:
			case self::AUTH_SERVICE_TWITTER:
				return true;
			default:
				return false;
		}
	}
}
