<?php
namespace User\Repository;
class UserRepository extends \Database\Repository\AbstractEntityRepository{

	/**
	 * Check if display name is available for use
	 * @param string $sUserDisplayName
	 * @throws \InvalidArgumentException
	 * @return boolean
	 */
	public function isUserDisplayNameAvailable($sUserDisplayName){
		if(empty($sUserDisplayName) || !is_string($sUserDisplayName))throw new \InvalidArgumentException(sprintf(
			'Display name expects string, "%s" given',
			empty($sUserDisplayName)?'':gettype($sUserDisplayName)
		));
		return !$this->findOneBy(array('user_display_name' => $sUserDisplayName));
	}

}