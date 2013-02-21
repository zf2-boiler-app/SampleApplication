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
		->setAuthAccessPublicKey($sPublicKey = $this->getServiceLocator()->get('AccessControlService')->generateAuthAccessPublicKey())
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
}