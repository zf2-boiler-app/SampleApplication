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
	public function authenticate($sAdapterName){
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

			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_AUTH_ACCESS_STATE_PENDING:
				return $this->getServiceLocator()->get('translator')->translate('auth_access_state_pending');
			//Unknown error
			default:
				return $this->getServiceLocator()->get('translator')->translate($iResult);
		}
	}

	/**
	 * @param string $sAuthAccessIdentity
	 * @throws \InvalidArgumentException
	 * @return boolean|string
	 */
	public function sendConfirmationResetCredential($sAuthAccessIdentity){
		if(empty($sAuthAccessIdentity) || !is_string($sAuthAccessIdentity))throw new \InvalidArgumentException(sprintf(
			'AuthAccess identity expects a not empty string, "%s" given',
			is_scalar($sAuthAccessIdentity)?$sAuthAccessIdentity:gettype($sAuthAccessIdentity)
		));

		$oAuthAccessRepository = $this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository');
		$aAvailableIdentities = $oAuthAccessRepository->getAvailableIdentities();
		$oAuthAccess = null;

		//Try retrieving existing AuthAccess for the giving identities
		while(!$oAuthAccess && $aAvailableIdentities){
			$sIdentityName = array_shift($aAvailableIdentities);
			if($sIdentityName === 'auth_access_email_identity' && !filter_var($sAuthAccessIdentity,FILTER_VALIDATE_EMAIL))continue;
			$oAuthAccess = $oAuthAccessRepository->findOneBy(array(
				$sIdentityName => $sAuthAccessIdentity
			));
		}

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		if(!$oAuthAccess)return $oTranslator->translate('identity_does_not_match_any_registered_user');

		//If AuthAccess is in pending state
		if($oAuthAccess->getAuthAccessState() !== \AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_ACTIVE_STATE)return $this->getServiceLocator()->get('translator')->translate('auth_access_pending');

		//Reset public key
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		$oAuthAccess->setAuthAccessPublicKey($oBCrypt->create($sPublicKey = $this->getServiceLocator()->get('AccessControlService')->generateAuthAccessPublicKey()));
		$oAuthAccessRepository->update($oAuthAccess);

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'auth_access_public_key' => $sPublicKey
		));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/authentication/confirm-reset-credential'),function($sHtml)use($oMessengerService,$oTranslator,$oAuthAccess){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oAuthAccess->getAuthAccessUser())
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
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function resetCredential($sPublicKey, $sEmailIdentity){
		if(empty($sPublicKey) || !is_string($sPublicKey))throw new \InvalidArgumentException('Public key expects a not empty string , "'.gettype($sPublicKey).'" given');
		if(empty($sEmailIdentity) || !is_string($sEmailIdentity))throw new \InvalidArgumentException('Email identity expects a not empty string , "'.gettype($sEmailIdentity).'" given');

		if(!($oAuthAccess = $this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->findOneBy(array(
			'auth_access_email_identity' => $sEmailIdentity
		))))throw new \LogicException(sprintf(
			'AuthAccess with email identity "%s" does not exist',
			$sEmailIdentity
		));

		//Verify public key
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		if(!$oBCrypt->verify($sPublicKey, $oAuthAccess->getAuthAccessPublicKey()))throw new \LogicException(sprintf(
			'Public key "%s" is not valid for email identity "%s"',
			$sPublicKey,$sEmailIdentity
		));
		//Check AuthAccess state
		elseif($oAuthAccess->getAuthAccessState() !== \AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_ACTIVE_STATE)throw new \LogicException(sprintf(
			'AuthAccess "%s" is not active',
			$oAuthAccess->getAuthAccessId()
		));

		//Reset credential
		$sCredential = md5(date('Y-m-d').str_shuffle(uniqid()));
		$oAuthAccess->setAuthAccessCredential($oBCrypt->create(md5($sCredential)));

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'auth_access_username_identity' => $sUsernameIdentity,
			'auth_access_credential' => $sCredential,
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
	 * Log out current logged user
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function logout(){
		$this->getServiceLocator()->get('AccessControlAuthenticationService')->clearIdentity();
		return $this;
	}
}