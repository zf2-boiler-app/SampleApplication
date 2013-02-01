<?php
namespace User\Service;
class UserService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \User\Service\UserService
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
	 * @return \User\Service\UserService
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
	 * @return \User\Service\UserService
	 */
	public function resendConfirmationEmail($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email is not a string');
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

		if($this->isUserEmailAvailable($sEmail) === true)return $oTranslator->translate('email_does_not_match_any_registered_user');
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
	 * @return \User\Service\UserService
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
		$oUserModel->changeUserPassword($oUser,md5($sPassword));

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
	 * @param string $sAdapterName
	 * @throws \Exception
	 * @return string|boolean
	 */
	public function login($sAdapterName){
		if(!is_string($sAdapterName))throw new \Exception('Adapter\'s name expects string, '.gettype($sAdapterName));

		//Performs authentication attempt
		switch($iResult = call_user_method_array(
			'authenticate',
			$this->getServiceLocator()->get('AuthService'),
			array_slice(func_get_args(),1)
		)){
			case \User\Authentication\UserAuthenticationService::AUTH_RESULT_VALID:
				return true;

			case \User\Authentication\UserAuthenticationService::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG:
				return $this->getServiceLocator()->get('translator')->translate('email_or_password_wrong');

			case \User\Authentication\UserAuthenticationService::AUTH_RESULT_USER_STATE_PENDING:
				return $this->getServiceLocator()->get('translator')->translate('user_state_pending');
			//Unknown error
			default:
				return $this->getServiceLocator()->get('translator')->translate($iResult);
		}
	}

	/**
	 * Log out current logged user
	 * @throws \Exception
	 * @return \User\Service\UserService
	 */
	public function logout(){
		$this->getServiceLocator()->get('UserAuthenticationService')->clearIdentity();
		return $this;
	}

	/**
	 * Get user entity from provider id
	 * @param \Hybrid_User_Profile $oUserProfile
	 * @param string $sService
	 * @throws \Exception
	 * @return \User\Entity\UserEntity
	 */
	public function getUserFromProvider(\Hybrid_User_Profile $oUserProfile,$sService){
		if(!is_string($sService))throw new \Exception('Authentication service ('.gettype($sService).') is not a string');
		if(($oUser = $this->getServiceLocator()->get('UserProviderModel')->getUser($oUserProfile->identifier,$sService)) instanceof \User\Entity\UserEntity)return $oUser;

		//Try to create user
		$oUserModel = $this->getServiceLocator()->get('UserModel');
		$oUser = $oUserModel->getUser($oUserModel->create(array(
			'user_email' => $oUserProfile->email,
			'user_state' => \User\Model\UserModel::USER_STATUS_ACTIVE
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
	 * @return \User\Entity\UserEntity
	 */
	public function getLoggedUser(){
		$iUserId = $this->getServiceLocator()->get('UserAuthenticationService')->getIdentity();
		//Prevent from session value error
		try{
			$oUser = $this->getServiceLocator()->get('UserModel')->getUser($iUserId);
		}
		catch(\Exception $oException){
			$this->logout();
			throw new \Exception('An error occurred when retrieving logged user');
		}
		if(!$oUser->isUserActive())throw new \Exception('User is not active');
		return $oUser;
	}

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean|string
	 */
	public function isUserEmailAvailable($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		if($this->getServiceLocator()->get('AuthService')->hasIdentity() && $this->getLoggedUser()->getUserEmail() === $sEmail)return str_ireplace(
			'%value%',
			$sEmail,
			$this->getServiceLocator()->get('translator')->translate(
				'The email address "%value%" is the same as currently used',
				'validator'
			)
		);
		return $this->getServiceLocator()->get('UserModel')->isUserEmailAvailable($sEmail)?true:str_ireplace(
			'%value%',
			$sEmail,
			$this->getServiceLocator()->get('translator')->translate(
				'The email adress "%value%" is unavailable',
				'validator'
			)
		);
	}
}