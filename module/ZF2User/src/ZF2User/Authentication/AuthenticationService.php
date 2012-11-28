<?php
namespace ZF2User\Authentication;
class AuthenticationService extends \Zend\Authentication\AuthenticationService{
	const AUTH_RESULT_UNREGISTERED_USER = -2;
	const AUTH_RESULT_USER_STATE_PENDING = -1;
	const AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG = 0;
	const AUTH_RESULT_VALID = 1;

	const AUTH_SERVICE_LOCAL = 'local';
	const AUTH_SERVICE_FACEBOOK = 'facebook';

	/**
	 * @var \Hybrid_Auth
	 */
	private $hybridAuthAdapter;

    /**
     * Constructor
     * @param \Zend\Authentication\Storage\StorageInterface $oStorage
     * @param \Zend\Authentication\Adapter\AdapterInterface $oAdapter
     */
    public function __construct(\Zend\Authentication\Storage\StorageInterface $oStorage, \Zend\Authentication\Adapter\AdapterInterface $oAdapter){
        $this->setStorage($oStorage)->setAdapter($oAdapter);
    }

    /**
     * @param \Hybrid_Auth $oAdapter
     * @return \ZF2User\Authentication\AuthenticationService
     */
    public function setHybridAuthAdapter(\Hybrid_Auth $oAdapter){
		$this->hybridAuthAdapter = $oAdapter;
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
        elseif($this->hybridAuthAdapter){
        	try{
        		$oUserProfile = $this->hybridAuthAdapter->authenticate($sService)->getUserProfile();
				//Retrieve user

        		$iUserId = get_user_by_provider_and_uid( $provider_name, $user_profile->identifier );


        		if( $user_id ){ // if user exist on database
        			// create a session for the user whithin your application
        			// and redirect him back to the profile or dashboard page
        			// ...
        		}
        		else return self::AUTH_RESULT_UNREGISTERED_USER;
        	}
        	catch(\Exception $oException){
        		throw new \Exception('Error append during authentication with HybridAuth : '.$oException->getMessage());
        	}
        }
        else throw new \Exception('Hybrid Auth adapter is undefined');

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
				return true;
			default:
				return false;
		}
	}
}
