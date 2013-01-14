<?php
namespace ZF2User\Entity;
class UserEntity extends \Database\Db\RowGateway\AbstractRowGateway{

	/**
	 * @param string $sEmail
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function setUserEmail($sEmail){
		if(!is_string($sEmail) || !filter_var($sEmail,FILTER_VALIDATE_EMAIL))throw new \Exception('Email expects valid email adress');
		return $this->offsetSet('user_email', $sEmail);
	}

	/**
	 * @param string $sPassword
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function setUserPassword($sPassword){
		if(!is_string($sPassword) || !preg_match('/^[a-f0-9]{32}$/', $sPassword))throw new \Exception('Password expects md5 hash');
		return $this->offsetSet('user_password', $sPassword);
	}


	/**
	 * @param string $sUserRegistrationKey
	 * @throws \Exception
	 * @return\ZF2User\Entity\UserEntity
	 */
	public function setUserRegistrationKey($sUserRegistrationKey){
		if(!is_string($sUserRegistrationKey)
		|| strlen($sUserRegistrationKey) !== 13)throw new \Exception('User registration key "'.$sUserRegistrationKey.'" is not valid');
		return $this->offsetSet('user_registration_key', $sUserRegistrationKey);
	}

	/**
	 * @param string $sUserState
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function setUserState($sUserState){
		if(!\ZF2User\Model\UserModel::userStateExists($sUserState))throw new \Exception('User state "'.$sUserState.'" is not valid');
		return $this->offsetSet('user_state', $sUserState);
	}

	/**
	 * @return boolean
	 */
	public function isUserActive(){
		return $this->getUserState() === \ZF2User\Model\UserModel::USER_STATUS_ACTIVE;
	}
}