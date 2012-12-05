<?php
namespace ZF2User\Model;
class UserModel extends \Application\Db\TableGateway\AbstractTableGateway{
	/** User model events **/
	const EVENT_USER_CREATED = 'user_created';
	
	/** User state */
	const USER_STATUS_PENDING = 'PENDING';
	const USER_STATUS_ACTIVE = 'ACTIVE';

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
	 * @param array $aUserInfos
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity
	 */
	public function create(array $aUserInfos){
		//Check values
		if(!isset($aUserInfos['user_email'])
		|| $sUserEmail = filter_var($aUserInfos['user_email'],FILTER_VALIDATE_EMAIL) === false
		|| (isset($aUserInfos['user_state']) && !self::userStateExists($aUserInfos['user_state'])))throw new \Exception('Infos for creating user infos are invalid');

		if(!$this->insert(array_intersect_key($aUserInfos, array_flip(array('user_email','user_state')))))throw new \Exception('An error occurred when creating a new user');
		$oUser = $this->select(array('user_id' => $this->getLastInsertValue()))->current();
		if(!($oUser instanceof \ZF2User\Entity\UserEntity))throw new \Exception('An error occurred when creating a new user');
		
		$this->getEventManager()->trigger(self::EVENT_USER_CREATED,$this,array('user' => $oUser));
		return $oUser;
	}

	public static function userStateExists($sUserState){
		switch($sUserState){
			case self::USER_STATUS_ACTIVE:
			case self::USER_STATUS_PENDING:
				return true;
			default:
				return false;
		}
	}
}
