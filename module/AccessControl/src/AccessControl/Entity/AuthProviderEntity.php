<?php
namespace AccessControl\Entity;
/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="\User\Repository\UserRepository")
 * @\Doctrine\ORM\Mapping\Table(name="users")
 */
class AuthProviderEntity extends \Database\Entity\AbstractEntity{

	/**
     * @var \User\Entity\UserEntity
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="User\Entity\UserEntity")
	 */
	protected $user;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Id
	 * @\Doctrine\ORM\Mapping\Column(type="string",length=50)
	 */
	protected $provider_id;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",length=255)
	 */
	protected $provider_name;

	/**
	 * @param \User\Entity\UserEntity $oUser
	 * @return \AccessControl\Entity\AuthProviderEntity
	 */
	public function setUser(\User\Entity\UserEntity $oUser){
		$this->user = $oUser;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUser(){
		return $this->user;
	}

	/**
	 * @param string $sProviderId
	 * @return \AccessControl\Entity\AuthProviderEntity
	 */
	public function setProviderId($sProviderId){
		$this->provider_id = $sProviderId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getProviderId(){
		return $this->provider_id;
	}

	/**
	 * @param string $sProviderName
	 * @return \AccessControl\Entity\AuthProviderEntity
	 */
	public function setProviderName($sProviderName){
		$this->provider_name = $sProviderName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getProviderName(){
		return $this->provider_name;
	}
}