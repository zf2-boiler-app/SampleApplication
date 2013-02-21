<?php
namespace User\Service;
class UserService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	/**
	 * Try to generate a unique display name
	 * @param string $sUserDisplayName
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 * @return string
	 */
	public function getAvailableUserDisplayName($sUserDisplayName){
		if(empty($sUserDisplayName) || !is_string($sUserDisplayName))throw new \InvalidArgumentException(sprintf(
			'Display name expects string, "%s" given',
			empty($sUserDisplayName)?'':gettype($sUserDisplayName)
		));

		//Nice generator
		$sOrginalUserDisplayName = $sUserDisplayName;
		$iIterator = 0;
		while($iIterator < 10){
			if($this->isUserDisplayNameAvailable($sUserDisplayName) === true)return $sUserDisplayName;
			$sUserDisplayName = $sOrginalUserDisplayName.rand(1000,9999);
		}

		//Hard generator
		$iIterator = 0;
		while($iIterator < 10){
			if($this->isUserDisplayNameAvailable($sUserDisplayName) === true)return $sUserDisplayName;
			$sUserDisplayName = $sOrginalUserDisplayName.str_shuffle(uniqid());
		}

		//Generate failed
		throw new \RuntimeException('Failed to generate a unique display name for "'.$sUserDisplayName.'"');
	}

	/**
	 * @param string $sUserDisplayName
	 * @throws \InvalidArgumentException
	 * @return boolean|string
	 */
	public function isUserDisplayNameAvailable($sUserDisplayName){
		if(empty($sUserDisplayName) || !is_string($sUserDisplayName))throw new \InvalidArgumentException(sprintf(
			'Display name expects string, "%s" given',
			empty($sUserDisplayName)?'':gettype($sUserDisplayName)
		));
		//If request is from logged user
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()
		&& $this->getServiceLocator()->get('AccessControlService')->getLoggedUser()->getUserDisplayName() === $sUserDisplayName)return str_ireplace(
			'%value%',$sUserDisplayName,
			$this->getServiceLocator()->get('translator')->translate('The display name "%value%" is the same as currently used','validator')
		);

		return $this->getServiceLocator()->get('User\Repository\UserRepository')->isUserDisplayNameAvailable($sUserDisplayName)?true:str_ireplace(
			'%value%',$sUserDisplayName,
			$this->getServiceLocator()->get('translator')->translate('The display name "%value%" is unavailable','validator')
		);
	}
}