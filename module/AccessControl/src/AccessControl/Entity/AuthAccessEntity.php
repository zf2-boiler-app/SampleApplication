<?php
namespace AccessControl\Entity;

/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="\AccessControl\Repository\AuthAccessRepository")
 * @\Doctrine\ORM\Mapping\Table(name="auth_access")
 */
class AuthAccessEntity extends \Database\Entity\AbstractEntity{
	/**
	 * @var int
	 * @\Doctrine\ORM\Mapping\Id
	 * @\Doctrine\ORM\Mapping\Column(type="integer")
	 * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
	 */
	protected $auth_access_id;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="email",unique=true)
	 */
	protected $auth_access_email_identity;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",unique=true,length=255)
	 */
	protected $auth_access_username_identity;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="md5hash")
	 */
	protected $auth_access_credential;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",length=60)
	 */
	protected $auth_access_public_key;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",length=60)
	 */
	protected $auth_access_state;

	/**
	 * @var \User\Entity\UserEntity
	 * @\Doctrine\ORM\Mapping\OneToOne(targetEntity="User\Entity\UserEntity")
     * @\Doctrine\ORM\Mapping\JoinColumn(name="auth_access_user_id", referencedColumnName="user_id")
	 */
	protected $auth_access_user;

	/**
	 * @return int
	 */
	public function getAuthAccessId(){
		return $this->auth_access_id;
	}

	/**
	 * @param string $sEmailIdentity
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessEmailIdentity($sEmailIdentity){
		$this->auth_access_email_identity = $sEmailIdentity;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthAccessEmailIdentity(){
		return $this->auth_access_email_identity;
	}

	/**
	 * @param string $sUsernameIdentity
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessUsernameIdentity($sUsernameIdentity){
		$this->auth_access_username_identity = $sUsernameIdentity;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthAccessUsernameIdentity(){
		return $this->auth_access_username_identity;
	}

	/**
	 * @param string $sCredential
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessCredential($sCredential){
		$this->auth_access_credential = $sCredential;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthAccessPublicKey(){
		return $this->auth_access_public_key;
	}

	/**
	 * @param string $sPublicKey
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessPublicKey($sPublicKey){
		$this->auth_access_public_key = $sPublicKey;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthAccessState(){
		return $this->auth_access_state;
	}

	/**
	 * @param string $sState
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessState($sState){
		$this->auth_access_state = $sState;
		return $this;
	}

	/**
	 * @param \User\Entity\UserEntity $oUser
	 * @return \AccessControl\Entity\AuthAccessEntity
	 */
	public function setAuthAccessUser(\User\Entity\UserEntity $oUser){
		$this->auth_access_user = $oUser;
		return $this;
	}

	/**
	 * @return \User\Entity\UserEntity
	 */
	public function getAuthAccessUser(){
		return $this->auth_access_user;
	}
}