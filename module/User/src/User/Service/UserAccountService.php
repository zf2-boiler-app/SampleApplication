<?php
namespace User\Service;
class UserAccountService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \User\Service\UserAccountService
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
	 * Delete current logged user
	 * @return \User\Service\UserAccountService
	 */
	public function deleteLoggedUser(){
		//Log out and Delete user
		$this->getServiceLocator()->get('AccessControlService')->logout()->getLoggedUser()->delete();
		return $this;
	}

	/**
	 * @param string $sAvatar
	 * @throws \Exception
	 * @return \User\Service\UserAccountService
	 */
	public function changeUserLoggedAvatar(array $aAvatarFileInfos){
		if(empty($aAvatarFileInfos['tmp_name']) || !is_readable($aAvatarFileInfos['tmp_name']))throw new \Exception('Avatar file not found : '.$aAvatarFileInfos['tmp_name']);

		$aImagesInfos = getimagesize($aAvatarFileInfos['tmp_name']);
		if(empty($aImagesInfos[2]))throw new \Exception('File type not found for avatar : '.$aAvatarFileInfos['tmp_name']);
		switch($aImagesInfos[2]){
			case IMAGETYPE_JPEG:
				$oImage = imagecreatefromjpeg($aAvatarFileInfos['tmp_name']);
				break;
			case IMAGETYPE_GIF:
				$oImage = imagecreatefromgif($aAvatarFileInfos['tmp_name']);
				break;
			case IMAGETYPE_PNG:
				$oImage = imagecreatefrompng($aAvatarFileInfos['tmp_name']);
				break;
			default:
				throw new \Exception('File type not supported for avatar : '.$aImagesInfos[2]);
		}
		//Crop image
		$oNewImage = imagecreatetruecolor(128,128);
		imagecopyresampled($oNewImage, $oImage, 0, 0, 0, 0, 128, 128, imagesx($oImage), imagesy($oImage));

		$aConfiguration = $this->getServiceLocator()->get('Config');
		if(empty($aConfiguration['paths']['avatarsPath'])
		|| !is_dir($aConfiguration['paths']['avatarsPath']))throw new \Exception('Avatars path is not a valid directory path : '.$aConfiguration['paths']['avatarsPath']);

		//Save avatar
		if(!imagepng(
			$oNewImage,
			$aConfiguration['paths']['avatarsPath'].DIRECTORY_SEPARATOR.$this->getServiceLocator()->get('AccessControlService')->getLoggedUser()->getUserId().'-avatar.png'
		))throw new \Exception('An error occurred when saving user avatar');
		return $this;
	}

	/**
	 * @param string $sPassword
	 * @throws \Exception
	 * @return \User\Service\UserAccountService
	 */
	public function changeUserLoggedPassword($sPassword){
		if(empty($sPassword) || !is_string($sPassword))throw new \Exception('Password ('.gettype($sPassword).') is not a string or is empty');
		$oUserModel = $this->getServiceLocator()->get('UserModel');

		$oUser = $this->getServiceLocator()->get('AccessControlService')->getLoggedUser();

		//Reset password
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
		$oMessengerService->renderView($oView->setTemplate('email/user/password-changed'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('change_password'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});
		return $this;
	}

	/**
	 * @param string $sPassword
	 * @return boolean
	 */
	public function checkUserLoggedPassword($sPassword){
		return $this->getServiceLocator()->get('UserModel')->checkUserPassword(
			$this->getServiceLocator()->get('UserService')->getLoggedUser(),
			md5($sPassword)
		);
	}

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return \User\Service\UserAccountService
	 */
	public function changeUserLoggedEmail($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email is ('.gettype($sEmail).') is not a string or is empty');
		$oUserModel = $this->getServiceLocator()->get('UserModel');

		$oUser = $this->getServiceLocator()->get('UserService')->getLoggedUser();

		//Reset password
		$oUserModel->changeUserEmail($oUser,$sEmail);

		//Reload user
		$oUser = $oUserModel->getUser($oUser->getUserId());

		//Create email view body
		$oView = new \Zend\View\Model\ViewModel(array(
			'user_email' => $oUser->getUserEmail(),
			'user_registration_key' => $oUser->getUserRegistrationKey()
		));

		//Retrieve translator
		$oTranslator = $this->getServiceLocator()->get('translator');

		//Retrieve Messenger service
		$oMessengerService = $this->getServiceLocator()->get('MessengerService');

		//Render view & send email to user
		$oMessengerService->renderView($oView->setTemplate('email/user/confirm-email'),function($sHtml)use($oMessengerService,$oTranslator,$oUser){
			$oMessage = new \Messenger\Message();
			$oMessengerService->sendMessage(
				$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
				->setTo($oUser)
				->setSubject($oTranslator->translate('change_email'))
				->setBody($sHtml),
				\Messenger\Service\MessengerService::MEDIA_EMAIL
			);
		});

		//Logout User
		$this->getServiceLocator()->get('UserService')->logout();

		return $this;
	}
}