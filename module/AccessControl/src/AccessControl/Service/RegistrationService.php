<?php
namespace AccessControl\Service;
class RegistrationService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	/**
	 * @param array $aRegisterData
	 * @throws \InvalidArgumentException
	 * @return \User\Service\UserService
	 */
	public function register(array $aRegisterData){
		if(!isset(
			$aRegisterData['auth_access_email_identity'],
			$aRegisterData['auth_access_username_identity'],
			$aRegisterData['auth_access_credential']
		))throw new \InvalidArgumentException('Register data is invalid');

		$sUsernameIdentity = $aRegisterData['auth_access_username_identity'];
		if(!is_string($sUsernameIdentity) || strpos($sUsernameIdentity, ' ') !== false)throw new \InvalidArgumentException(sprintf(
			'UsernameIdentity expects string without spaces "%s" given',
			is_string($sUsernameIdentity)?$sUsernameIdentity:gettype($sUsernameIdentity)
		));

		//Create User
		$oUser = new \User\Entity\UserEntity();
		//Set default display name
		$oUser->setUserDisplayName($this->getServiceLocator()->get('UserService')->getAvailableUserDisplayName(ucfirst($sUsernameIdentity)));
		$this->getServiceLocator()->get('User\Repository\UserRepository')->create($oUser);

		$sEmailIdentity = $aRegisterData['auth_access_email_identity'];
		if(!is_string($sEmailIdentity) || !filter_var($sEmailIdentity))throw new \InvalidArgumentException(sprintf(
			'EmailIdentity expects valid email "%s" given',
			is_string($sEmailIdentity)?$sEmailIdentity:gettype($sEmailIdentity)
		));

		$sCredential = $aRegisterData['auth_access_credential'];
		if(!is_string($sCredential))throw new \InvalidArgumentException(sprintf(
			'Credential expects string "%s" given',
			gettype($sCredential)
		));

		//Crypter
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();

		//Create AuthAccess
		$oAuthAccess = new \AccessControl\Entity\AuthAccessEntity();
		$oAuthAccess->setAuthAccessEmailIdentity($sEmailIdentity)
		->setAuthAccessUsernameIdentity($sUsernameIdentity)

		//Set crypted credential
		->setAuthAccessCredential($oBCrypt->create(md5($sCredential)))
		->setAuthAccessPublicKey($oBCrypt->create($sPublicKey = $this->getServiceLocator()->get('AccessControlService')->generateAuthAccessPublicKey()))
		->setAuthAccessState(\AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_PENDING_STATE)
		->setAuthAccessUser($oUser);

		$this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->create($oAuthAccess);

		//Set auth access to user
		$oUser->setUserAuthAccess($oAuthAccess);

		//Send email confirmation to user

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'auth_access_email_identity' => $sEmailIdentity,
			'auth_access_username_identity' => $sUsernameIdentity,
			'auth_access_credential' => $sCredential,
			'auth_access_public_key' => $sPublicKey
		));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/registration/confirm-email'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
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
	 * @param string $sRegistrationKey
	 * @throws \InvalidArgumentException
	 * @return boolean|string
	 */
	public function confirmEmail($sPublicKey, $sEmailIdentity){
		if(empty($sPublicKey) || !is_string($sPublicKey))throw new \InvalidArgumentException('Public key expects a not empty string , "'.gettype($sPublicKey).'" given');
		if(empty($sEmailIdentity) || !is_string($sEmailIdentity))throw new \InvalidArgumentException('Email identity expects a not empty string , "'.gettype($sEmailIdentity).'" given');

		if(!($oAuthAccess = $this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->findOneBy(array(
			'auth_access_email_identity' => $sEmailIdentity
		))))throw new \LogicException(sprintf(
			'AuthAccess with email identity "%s" does not exist',
			$sEmailIdentity
		));

		//Crypter
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		if(!$oBCrypt->verify($sPublicKey, $oAuthAccess->getAuthAccessPublicKey()))throw new \LogicException(sprintf(
			'Public key "%s" is not valid for email identity "%s"',
			$sPublicKey,$sEmailIdentity
		));
		elseif($oAuthAccess->getAuthAccessState() === \AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_ACTIVE_STATE)return $this->getServiceLocator()->get('translator')->translate('email_already_confirmed');

		//Active AuthAccess
		$oAuthAccess->setAuthAccessState(\AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_ACTIVE_STATE);
		$this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->update($oAuthAccess);

		return true;
	}

	/**
	 * @param string $sAuthAccessIdentity
	 * @throws \InvalidArgumentException
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function resendConfirmationEmail($sAuthAccessIdentity){
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

		//Reset public key
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		$oAuthAccess->setAuthAccessPublicKey($oBCrypt->create($sPublicKey = $this->getServiceLocator()->get('AccessControlService')->generateAuthAccessPublicKey()));
		$oAuthAccessRepository->update($oAuthAccess);

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'auth_access_public_key' => $sPublicKey,
			'auth_access_email_identity' => $oAuthAccess->getAuthAccessEmailIdentity()
		));

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/registration/confirm-email'),function($sHtml)use($oMessengerService,$oTranslator,$oAuthAccess){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oAuthAccess->getAuthAccessUser())
				->setSubject($oTranslator->translate('register'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return $this;
	}
}