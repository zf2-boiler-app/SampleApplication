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

		if($this->insert(array_intersect_key($aUserInfos, array_flip(array('user_email','user_password','user_state')))))return $this->getLastInsertValue(); 
		throw new \Exception('An error occurred when creating a new user');
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
	 * Check if an email is available for use
	 * @param string $sEmail
	 * @throws \Exception
	 * @return boolean
	 */
	public function isUserEmailAvailable($sEmail){
		if(empty($sEmail) || !is_string($sEmail))throw new \Exception('Email si not a string');
		return !$this->select(array('user_email' => $sEmail))->count();
	}
}