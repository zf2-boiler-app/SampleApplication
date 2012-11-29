<?php
namespace ZF2User\Entity;
class UserEntity{
	/**
	 * @param array $aDatas
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function exchangeArray($aDatas){
		foreach($aDatas as $sChamps => $sValeur){
			$this->$sChamps = $sValeur;
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray(){
		return get_object_vars($this);
	}

	/**
	 * @throws \Exception
	 * @return string
	 */
	public function getUserState(){
		if(!isset($this->user_state))throw new\Exception('user_state is undefined');
		return $this->user_state;
	}

	/**
	 * @return boolean
	 */
	public function isUserActive(){
		return $this->getUserState() === \ZF2User\Model\UserModel::USER_STATUS_ACTIVE;
	}
}