<?php
namespace ZF2User\Service;
class UserService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

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

	/**
	 * @param string $sUserEmail
	 * @param string $sUserPassword
	 * @throws \Exception
	 * @return \ZF2User\Service\UserService
	 */
	public function register($sUserEmail,$sUserPassword){
		if(empty($sUserEmail) || empty($sUserPassword) || !is_string($sUserEmail) || !is_string($sUserPassword))throw new \Exception('User\'s email ('.gettype($sUserEmail).') and/or user\'s ('.gettype($sUserPassword).') password are not strings or are empty');
		//Create & retrieve user
		$oUser = $this->getServiceLocator()->get('UserModel')->getUser($this->getServiceLocator()->get('UserModel')->create(array(
			'user_email' => $sUserEmail,
			'user_password' => md5($sUserPassword)
		)));

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
    		'user_email' => $oUser->getUserEmail(),
    		'user_password' => $sUserPassword,
    		'user_registration_key' => $oUser->getUserRegistrationKey()
    	));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/user/confirm-email'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('register'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return $this;
	}

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return \ZF2User\Service\UserService
	 */
	public function resendConfirmationEmail($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		$oUser = $this->getServiceLocator()->get('UserModel')->getUserByEmail($sEmail);

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'user_registration_key' => $oUser->getUserRegistrationKey()
		));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/user/confirm-email'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('register'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return $this;
	}

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean|string
	 */
	public function sendConfirmationResetPassword($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		if($this->isUserEmailAvailable($sEmail))return $oTranslator->translate('email_does_not_match_any_registered_user');
		$oUser = $this->getServiceLocator()->get('UserModel')->getUserByEmail($sEmail);

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'reset_key' => $oUser->getUserRegistrationKey()
		));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/user/confirm-reset-password'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('reset_password'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return true;
	}

	/**
	 * @param string $sRegistrationKey
	 * @throws \Exception
	 * @return boolean|string
	 */
	public function confirmEmail($sRegistrationKey){
		if(empty($sRegistrationKey) || !is_string($sRegistrationKey))throw new \Exception('User\'s registration key ('.gettype($sRegistrationKey).') is not a string or is empty');
		$oUserModel = $this->getServiceLocator()->get('UserModel');

		$oUser = $oUserModel->getUserByRegistrationKey($sRegistrationKey);
		if($oUser->isUserActive())return $this->getServiceLocator()->get('translator')->translate('email_already_confirmed');
		//Active user
		$oUserModel->activeUser($oUser);
		return true;
	}

	/**
	 * @param string $sResetKey
	 * @throws \Exception
	 * @return \ZF2User\Service\UserService
	 */
	public function resetPassword($sResetKey){
		if(empty($sResetKey) || !is_string($sResetKey))throw new \Exception('Reset key ('.gettype($sResetKey).') is not a string or is empty');
		$oUserModel = $this->getServiceLocator()->get('UserModel');

		$oUser = $oUserModel->getUserByRegistrationKey($sResetKey);
		if(!$oUser->isUserActive())throw new \Exception('User is not active');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Reset password
		$sPassword = md5(date('Y-m-d').str_shuffle(uniqid()));
		$oUserModel->resetUserPassword($oUser,md5($sPassword));

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'user_email' => $oUser->getUserEmail(),
			'user_password' => $sPassword
		));

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/user/password-reset'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('reset_password'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return $this;
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
	 * Log out current logged user
	 * @throws \Exception
	 * @return \ZF2User\Service\UserService
	 */
	public function logout(){
		/* @var $oAuthService \ZF2User\Authentication\AuthenticationService */
		$oAuthService = $this->getServiceLocator()->get('AuthService');
		if(!$oAuthService->hasIdentity())throw new \Exception('There is no logged user');
		//Clear auth storage
		$oAuthService->clearIdentity();

		//Clear providers storage
		$this->getServiceLocator()->get('HybridAuthAdapter')->logoutAllProviders();
		return $this;
	}


	/**
	 * Delete current logged user
	 * @return \ZF2User\Service\UserService
	 */
	public function deleteLoggedUser(){
		//Delete user
		$this->getServiceLocator()->get('UserModel')->deleteUser($this->getLoggedUser());
		//Log out user
		$this->logout();
		return $this;
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
		$oUserModel = $this->getServiceLocator()->get('UserModel');
		$oUser = $oUserModel->getUser($oUserModel->create(array(
			'user_email' => $oUserProfile->email,
			'user_state' => \ZF2User\Model\UserModel::USER_STATUS_ACTIVE
		)));

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
		return $this->getServiceLocator()->get('UserModel')->getUser($oAuthService->getIdentity());
	}

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean
	 */
	public function isUserEmailAvailable($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		return $this->getServiceLocator()->get('UserModel')->isUserEmailAvailable($sEmail);
	}
}