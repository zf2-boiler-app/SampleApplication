<?php
namespace AccessControl\Service;
class AccessControlService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	const LOCAL_AUTHENTICATION = 'LocalAuth';
	const HYBRID_AUTH_AUTHENTICATION = 'HybridAuth';

	/**
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @return \User\Entity\UserEntity
	 */
	public function getLoggedUser(){
		$iUserId = $this->getServiceLocator()->get('AccessControlAuthenticationService')->getIdentity();

		//Prevent from session value error
		try{
			$oUser = $this->getServiceLocator()->get('User\Repository\UserRepository')->find($iUserId);
		}
		catch(\Exception $oException){
			$this->logout();
			throw new \RuntimeException('An error occurred when retrieving logged user');
		}
		if(!$oUser->isUserActive())throw new \LogicException('User is not active');
		return $oUser;
	}


	/**
	 * @param string $sEmailIdentity
	 * @throws \InvalidArgumentException
	 * @return boolean|string
	 */
	public function isEmailIdentityAvailable($sEmailIdentity){
		if(empty($sEmailIdentity) || !is_string($sEmailIdentity))throw new \InvalidArgumentException('Email expects string, "'.(empty($sEmailIdentity)?'':gettype($sEmailIdentity)).'" given');

		$oTranslator = $this->getServiceLocator()->get('translator');

		//If request is from logged user
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity() && $this->getLoggedUser()->getUserEmail() === $sEmailIdentity)return str_ireplace(
			array('%identityName%','%value%'),array($oTranslator->translate('the_email'),$sEmailIdentity),
			$oTranslator->translate('The %identityName% "%value%" is the same as currently used','validator')
		);

		return $this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->isIdentityEmailAvailable($sEmailIdentity)?true:str_ireplace(
			array('%identityName%','%value%'),array($oTranslator->translate('the_email'),$sEmailIdentity),
			$oTranslator->translate('The %identityName% "%value%" is unavailable','validator')
		);
	}

	/**
	 * @param string $sUsernameIdentity
	 * @throws \InvalidArgumentException
	 * @return boolean|string
	 */
	public function isUsernameIdentityAvailable($sUsernameIdentity){
		if(empty($sUsernameIdentity) || !is_string($sUsernameIdentity))throw new \InvalidArgumentException('Username identity expects string, "'.(empty($sUsernameIdentity)?'':gettype($sUsernameIdentity)).'" given');

		$oTranslator = $this->getServiceLocator()->get('translator');

		//If request is from logged user
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity() && $this->getLoggedUser()->getUserEmail() === $sUsernameIdentity)return str_ireplace(
			array('%identityName%','%value%'),array($oTranslator->translate('the_username'),$sUsernameIdentity),
			$oTranslator->translate('The %identityName% "%value%" is the same as currently used','validator')
		);

		return $this->getServiceLocator()->get('AccessControl\Repository\AuthAccessRepository')->isIdentityUserNameAvailable($sUsernameIdentity)?true:str_ireplace(
			array('%identityName%','%value%'),array($oTranslator->translate('the_username'),$sUsernameIdentity),
			$oTranslator->translate('The %identityName% "%value%" is unavailable','validator')
		);
	}

	/**
	 * Generate a random public crypted key
	 * @return string
	 */
	public function generateAuthAccessPublicKey(){
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		return $oBCrypt->create(str_shuffle(uniqid(str_shuffle(uniqid()))));
	}
}