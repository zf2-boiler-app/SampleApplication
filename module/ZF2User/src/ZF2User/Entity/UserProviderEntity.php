<?php
namespace ZF2User\Entity;
class UserProviderEntity{
	/**
	 * @param array $aDatas
	 * @return \ZF2User\Entity\UserProviderEntity
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
	public function getUserId(){
		if(!isset($this->user_id))throw new\Exception('user_id is undefined');
		return $this->user_id;
	}
}