<?php
namespace ZF2User\Model;
class UserModel extends \Application\Db\TableGateway\AbstractTableGateway{
	/** User model events **/
	const EVENT_USER_CREATED = 'user_created';

	/** User state */
	const USER_STATUS_PENDING = 'PENDING';
	const USER_STATUS_ACTIVE = 'ACTIVE';

	/**
	 * @var string
	 */
	protected $primary = 'user_id';

	/**
	 * Constuctor
	 * @param Adapter $oAdapter
	 */
	public function __construct(\Zend\Db\Adapter\Adapter $oAdapter){
		parent::__construct(
			'users',
			$oAdapter,
			null,
			new \Zend\Db\ResultSet\ResultSet(\Zend\Db\ResultSet\ResultSet::TYPE_ARRAYOBJECT,new \ZF2User\Entity\UserEntity())
		);
	}

	/**
	 * Retrieve User entity from User ID
	 * @param int $iUserId
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function getUser($iUserId){
		if(!is_int($iUserId))throw new \Exception('User ID ('.gettype($iUserId).') is not an int');
		if(($oUser = $this->select(array('user_id'=>$iUserId))->current()) instanceof \ZF2User\Entity\UserEntity)return $oUser;
		throw new \Exception('User id doesn\'t match with registred user : '.$iUserId);
	}

	/**
	 * Retrieve User entity from User registration key
	 * @param string $sRegistrationKey
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function getUserByEmail($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('User\'s email ('.gettype($sEmail).') is not a string or is empty');
		if(($oUser = $this->select(array('user_email' => $sEmail))->current()) instanceof \ZF2User\Entity\UserEntity)return $oUser;
		throw new \Exception('User email doesn\'t match with registred user : '.$sEmail);
	}

	/**
	 * Retrieve User entity from User registration key
	 * @param string $sRegistrationKey
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function getUserByRegistrationKey($sRegistrationKey){
		if(empty($sRegistrationKey) || !is_string($sRegistrationKey))throw new \Exception('User\'s registration key ('.gettype($sRegistrationKey).') is not a string or is empty');
		if(($oUser = $this->select(array('user_registration_key' => $sRegistrationKey))->current()) instanceof \ZF2User\Entity\UserEntity)return $oUser;
		throw new \Exception('User registration key doesn\'t match with registred user : '.$sRegistrationKey);
	}

	/**
	 * @param array $aUserInfos
	 * @throws \Exception
	 * @return int : id of the newly created user entity
	 */
	public function create(array $aUserInfos){
		//Check values
		if(!isset($aUserInfos['user_email'])
		|| ($aUserInfos['user_email'] = filter_var($aUserInfos['user_email'],FILTER_VALIDATE_EMAIL)) === false
		|| !$this->isUserEmailAvailable($aUserInfos['user_email'])
		|| (isset($aUserInfos['user_state']) && !self::userStateExists($aUserInfos['user_state'])))throw new \Exception('Infos for creating user infos are invalid');

		//Generate user registration key
		$aUserInfos['user_registration_key'] = $this->generateRegistrationKey();

		if($this->insert(array_intersect_key($aUserInfos, array_flip(array('user_email','user_password','user_registration_key','user_state')))))return (int)$this->getLastInsertValue();
		throw new \Exception('An error occurred when creating a new user');
	}


	/**
	 * Delete user
	 * @param \ZF2User\Entity\UserEntity $oUser
	 * @throws \Exception
	 * @return \ZF2User\Model\UserModel
	 */
	public function deleteUser(\ZF2User\Entity\UserEntity $oUser){
		//Update user state and registration key
		if(!$this->delete(array('user_id' => $oUser->getUserId())))throw new \Exception('An error occurred when deleting user');
		return $this;
	}

	/**
	 * Update user state to "Active"
	 * @param \ZF2User\Entity\UserEntity $oUser
	 * @throws \Exception
	 * @return \ZF2User\Model\UserModel
	 */
	public function activeUser(\ZF2User\Entity\UserEntity $oUser){
		//Update user state and registration key
		if(!$this->update(array(
			'user_state'=> self::USER_STATUS_ACTIVE,
			'user_registration_key' => $this->generateRegistrationKey()
		),array('user_id' => $oUser->getUserId())))throw new \Exception('An error occurred when updating user state');
		return $this;
	}

	/**
	 * Change user password
	 * @param \ZF2User\Entity\UserEntity $oUser
	 * @param string $sPassword
	 * @throws \Exception
	 * @return \ZF2User\Model\UserModel
	 */
	public function changeUserPassword(\ZF2User\Entity\UserEntity $oUser,$sPassword){
		if(!is_string($sPassword) || !preg_match('/^[a-f0-9]{32}$/', $sPassword))throw new \Exception('Password expects md5 hash');
		//Update user password and registration key
		if(!$this->update(array(
			'user_password'=> $sPassword,
			'user_registration_key' => $this->generateRegistrationKey()
		),array('user_id' => $oUser->getUserId())))throw new \Exception('An error occurred when updating user password');
		return $this;
	}

	/**
	 * Change user password
	 * @param \ZF2User\Entity\UserEntity $oUser
	 * @param string $sPassword
	 * @throws \Exception
	 * @return \ZF2User\Model\UserModel
	 */
	public function changeUserEmail(\ZF2User\Entity\UserEntity $oUser,$sEmail){
		if(!is_string($sEmail) || !filter_var($sEmail,FILTER_VALIDATE_EMAIL))throw new \Exception('Email expects valid email adress');
		//Update user email, state and registration key
		if(!$this->update(array(
			'user_email'=> $sEmail,
			'user_registration_key' => $this->generateRegistrationKey(),
			'user_state' => self::USER_STATUS_PENDING
		),array('user_id' => $oUser->getUserId())))throw new \Exception('An error occurred when updating user email');
		return $this;
	}

	/**
	 * Check if an email is available for use
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean
	 */
	public function isUserEmailAvailable($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		return !$this->select(array('user_email' => $sEmail))->count();
	}

	/**
	 * @param string $sPassword
	 * @param \ZF2User\Entity\UserEntity $oUser
	 * @throws \Exception
	 * @return boolean
	 */
	public function checkUserPassword(\ZF2User\Entity\UserEntity $oUser, $sPassword){
		if(!is_string($sPassword) || !preg_match('/^[a-f0-9]{32}$/', $sPassword))throw new \Exception('Password expects md5 hash');
		return !!$this->select(array(
			'user_id' => $oUser->getUserId(),
			'user_password' => $sPassword
		))->count();
	}

	/**
	 * @param string $sUserState
	 * @return boolean
	 */
	public static function userStateExists($sUserState){
		switch($sUserState){
			case self::USER_STATUS_ACTIVE:
			case self::USER_STATUS_PENDING:
				return true;
			default:
				return false;
		}
	}

	/**
	 * Generate a unique registration key
	 * @return string
	 */
	protected function generateRegistrationKey(){
		$iIterator = 0;
		$sRegistrationKey = str_shuffle(uniqid());
		while($this->select(array('user_registration_key' => $sRegistrationKey))->count() && $iIterator < 5){
			$sRegistrationKey = str_shuffle(uniqid());
		}
		return $iIterator > 5 ?uniqid():$sRegistrationKey;
	}
}