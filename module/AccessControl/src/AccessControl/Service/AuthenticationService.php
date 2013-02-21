<?php
namespace AccessControl\Service;
class AuthenticationService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	const LOCAL_AUTHENTICATION = 'LocalAuth';
	const HYBRID_AUTH_AUTHENTICATION = 'HybridAuth';

	/**
	 * Login user
	 * @param string $sAdapterName
	 * @throws \InvalidArgumentException
	 * @return string|boolean
	 */
	public function login($sAdapterName){
		if(!is_string($sAdapterName))throw new \InvalidArgumentException('Adapter\'s name expects string, "'.gettype($sAdapterName).'" given');

		//Performs authentication attempt
		switch($iResult = call_user_func_array(
			array($this->getServiceLocator()->get('AccessControlAuthenticationService'),'authenticate'),
			func_get_args()
		)){
			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_VALID:
				return true;

			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG:
				return $this->getServiceLocator()->get('translator')->translate('email_or_password_wrong');

			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_USER_STATE_PENDING:
				return $this->getServiceLocator()->get('translator')->translate('user_state_pending');
			//Unknown error
			default:
				return $this->getServiceLocator()->get('translator')->translate($iResult);
		}
	}

	/**
	 * Log out current logged user
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function logout(){
		$this->getServiceLocator()->get('AccessControlAuthenticationService')->clearIdentity();
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
}