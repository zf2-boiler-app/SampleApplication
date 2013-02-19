<?php
namespace User\Service;
class UserService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

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
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean|string
	 */
	public function isUserEmailAvailable($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		if($this->getServiceLocator()->get('UserAuthenticationService')->hasIdentity() && $this->getLoggedUser()->getUserEmail() === $sEmail)return str_ireplace(
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