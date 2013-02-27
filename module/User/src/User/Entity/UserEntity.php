<?php
namespace User\Entity;
/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="\User\Repository\UserRepository")
 * @\Doctrine\ORM\Mapping\Table(name="users")
 */
class UserEntity extends \Database\Entity\AbstractEntity{

	/**
	 * @var int
	 * @\Doctrine\ORM\Mapping\Id
	 * @\Doctrine\ORM\Mapping\Column(type="integer")
	 * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
	 */
	protected $user_id;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",unique=true,length=255)
	 */
	protected $user_display_name;

	/**
	 * @var \AccessControl\Entity\AuthAccessEntity
     * @\Doctrine\ORM\Mapping\OneToOne(targetEntity="AccessControl\Entity\AuthAccessEntity", mappedBy="auth_access_user")
	 */
	protected $user_auth_access;

	/**
	 * @return int
	 */
	public function getUserId(){
		return $this->user_id;
	}

	/**
	 * @param string $sDisplayName
	 * @return \User\Entity\UserEntity
	 */
	public function setUserDisplayName($sDisplayName){
		$this->user_display_name = $sDisplayName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserDisplayName(){
		return $this->user_display_name;
	}

	/**
	 * @param \AccessControl\Entity\AuthAccessEntity $oUserAuthAccess
	 * @return \User\Entity\UserEntity
	 */
	public function setUserAuthAccess(\AccessControl\Entity\AuthAccessEntity $oUserAuthAccess){
		$this->user_auth_access = $oUserAuthAccess;
		return $this;
	}

	/**
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function getUserAuthAccess(){
		foreach(get_object_vars($this) as $sKey => $aVar){
			/* TODO Remove Error log */error_log(print_r($sKey.' : '.(is_object($aVar)?get_class($aVar):gettype($aVar)),true));
		}
		return $this->user_auth_access;
	}
}