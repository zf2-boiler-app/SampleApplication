<?php
namespace ZF2User\Service;
class UserService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	public function __construct(){

	}

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \ZF2User\Service\UserService
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$this->serviceLocator = $oServiceLocator;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceLocator(){
		if($this->serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorInterface)return $this->serviceLocator;
		throw new \Exception('Service Locator is undefined');
	}

	public function register($sUserEmail,$sUserPassword){
		if(empty($sUserEmail) || empty($sUserPassword) || !is_string($sUserEmail) || !is_string($sUserPassword))throw new \Exception('User\'s email ('.gettype($sUserEmail).') and/or user\'s ('.gettype($sUserPassword).') password are not strings or are empty');
		return true;
	}

	/**
	 * Login user
	 * @param string $sUserEmail
	 * @param string $sUserPassword
	 * @param string $sService
	 * @throws \Exception
	 * @return string|boolean
	 */
	public function login($sUserEmail = null,$sUserPassword = null,$sService = \ZF2User\Authentication\AuthenticationService::AUTH_SERVICE_LOCAL){
		if(!is_string($sService))throw new \Exception('Authentication service ('.gettype($sService).') is not a string');

		$oAuthService = $this->getServiceLocator()->get('AuthService');
		if(!($bIsLocalAuth = $sService === \ZF2User\Authentication\AuthenticationService::AUTH_SERVICE_LOCAL))$oAuthService
		->setUserService($this);
		elseif(!is_string($sUserEmail) || !is_string($sUserPassword))throw new \Exception('User\'s email ('.gettype($sUserEmail).') or user\'s ('.gettype($sUserPassword).') password are not strings');

		switch($iResult = $oAuthService->login($sUserEmail,$sUserPassword,$sService)){
			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_HYBRID_AUTH_UNAVAILABLE:
				if($bIsLocalAuth)throw new \Exception('Invalid Authentication Service return code with local authentication service');
				return sprintf($this->getServiceLocator()->get('translator')->translate('authentification_provider_unavailable'),$sService);

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_HYBRID_AUTH_CANCELED:
				if($bIsLocalAuth)throw new \Exception('Invalid Authentication Service return code with local authentication service');
				return sprintf($this->getServiceLocator()->get('translator')->translate('provider_authentification_canceled'),$sService);

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED:
				if($bIsLocalAuth)throw new \Exception('Invalid Authentication Service return code with local authentication service');
				return sprintf($this->getServiceLocator()->get('translator')->translate('user_not_connected_to_authentification_provider'),$sService);

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_UNREGISTERED_USER:
				if($bIsLocalAuth)throw new \Exception('Invalid Authentication Service return code with local authentication service');
				return sprintf($this->getServiceLocator()->get('translator')->translate('unregistered_user_please_sign_in'),$sService);

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG:
				return $this->getServiceLocator()->get('translator')->translate('email_or_password_wrong');

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_USER_STATE_PENDING:
				return $this->getServiceLocator()->get('translator')->translate('user_state_pending');

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_VALID:
				return true;

			default:
				throw new \Exception('Unknown Authentication Service return code : '.$iResult);
		}
	}

	/**
	 * @throws \Exception
	 * @return boolean
	 */
	public function logout(){
		/* @var $oAuthService \ZF2User\Authentication\AuthenticationService */
		$oAuthService = $this->getServiceLocator()->get('AuthService');
		if(!$oAuthService->hasIdentity())throw new \Exception('There is no logged user');
		//Clear auth storage
		$oAuthService->clearIdentity();

		//Clear providers storage
		$this->getServiceLocator()->get('HybridAuthAdapter')->logoutAllProviders();
		return true;
	}

	/**
	 * Get user entity from provider id
	 * @param \Hybrid_User_Profile $oUserProfile
	 * @param string $sService
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function getUserFromProvider(\Hybrid_User_Profile $oUserProfile,$sService){
		if(!is_string($sService))throw new \Exception('Authentication service ('.gettype($sService).') is not a string');
		if(($oUser = $this->getServiceLocator()->get('UserProviderModel')->getUser($oUserProfile->identifier,$sService)) instanceof \ZF2User\Entity\UserEntity)return $oUser;

		//Try to create user
		$oUser = $this->getServiceLocator()->get('UserModel')->create(array(
			'user_email' => $oUserProfile->email,
			'user_state' => \ZF2User\Model\UserModel::USER_STATUS_ACTIVE
		));

		//Link to user provider
		$this->getServiceLocator()->get('UserProviderModel')->create(array(
			'user_id' => $oUser->getUserId(),
			'provider_id' => $oUserProfile->identifier,
			'provider_name' => $sService
		));

		return $oUser;
	}

	/**
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function getLoggedUser(){
		$oAuthService = $this->getServiceLocator()->get('AuthService');
		if(!$oAuthService->hasIdentity())throw new \Exception('There is no logged user');
		if(($oUser = $this->getServiceLocator()->get('UserModel')->select(array('user_id'=>$oAuthService->getIdentity()))->current()) instanceof \ZF2User\Entity\UserEntity)return $oUser;
		else throw new \Exception('User logged doesn\'t match with registred user : '.$oAuthService->$this->getIdentity());
	}
}