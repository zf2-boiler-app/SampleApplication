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
	 * @\Doctrine\ORM\Mapping\Column(type="email",unique=true)
	 */
	protected $user_email;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="md5hash")
	 */
	protected $user_password;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="string",length=13)
	 */
	protected $user_registration_key;

	/**
	 * @var string
	 * @\Doctrine\ORM\Mapping\Column(type="userstateenum")
	 */
	protected $user_state;

	/**
	 * @return int
	 */
	public function getUserId(){
		return $this->user_id;
	}

	/**
	 * @param string $sEmail
	 * @return \User\Entity\UserEntity
	 */
	public function setUserEmail($sEmail){
		$this->user_email = $sEmail;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserEmail(){
		return $this->user_email;
	}

	/**
	 * @param string $sPassword
	 * @return \User\Entity\UserEntity
	 */
	public function setUserPassword($sPassword){
		$this->user_password = $sPassword;
		return $this;
	}

	/**
	 * @param string $sUserRegistrationKey
	 * @return \User\Entity\UserEntity
	 */
	public function setUserRegistrationKey($sUserRegistrationKey){
		$this->user_registration_key = $sUserRegistrationKey;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserRegistrationKey(){
		return $this->user_registration_key;
	}

	/**
	 * @param string $sUserState
	 * @return \User\Entity\UserEntity
	 */
	public function setUserState($sUserState){
		$this->user_state = $sUserState;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserState(){
		return $this->user_state;
	}

	/**
	 * @return boolean
	 */
	public function isUserActive(){
		return $this->getUserState() === \User\Model\UserModel::USER_STATUS_ACTIVE;
	}
}