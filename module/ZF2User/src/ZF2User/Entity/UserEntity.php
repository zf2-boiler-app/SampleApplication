<?php
namespace ZF2User\Entity;
class UserEntity{
	public function exchangeArray($aDatas){
		foreach($aDatas as $sChamps => $sValeur){
			$this->$sChamps = $sValeur;
		}
	}

	public function toArray(){
		return get_object_vars($this);
	}
}