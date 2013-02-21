<?php
namespace AccessControl\Repository;
class AuthProviderRepository extends \Database\Repository\AbstractEntityRepository{

	/**
	 * @param \AccessControl\Entity\AuthAccessEntity $oAuthAccessEntity
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function create(\AccessControl\Entity\AuthAccessEntity $oAuthAccessEntity){
		//Set AuthAccess public key to entity
		return parent::create($oAuthAccessEntity->setAuthAccessPublicKey($this->generateAuthAccessPublicKey()));
	}

	/**
	 * @param \AccessControl\Entity\AuthAccessEntity $oAuthAccessEntity
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function update(\AccessControl\Entity\AuthAccessEntity $oAuthAccessEntity){
		//Set new AuthAccess public key to entity for safety reasons
		return parent::update($oAuthAccessEntity->setAuthAccessPublicKey($this->generateAuthAccessPublicKey()));
	}
}