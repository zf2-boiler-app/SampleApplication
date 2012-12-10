<?php
namespace ZF2User\Model;
class UserProviderModel extends \Application\Db\TableGateway\AbstractTableGateway{
	private $userModel;

	/**
	 * Constuctor
	 * @param Adapter $oAdapter
	 */
	public function __construct(\Zend\Db\Adapter\Adapter $oAdapter,\ZF2User\Model\UserModel $oUserModel){
		$this->userModel = $oUserModel;
		parent::__construct(
			'users_providers',
			$oAdapter,
			null,
			new \Zend\Db\ResultSet\ResultSet(\Zend\Db\ResultSet\ResultSet::TYPE_ARRAYOBJECT,new \ZF2User\Entity\UserProviderEntity())
		);
	}

	/**
	 * Retrieve user entity from user provider
	 * @param string $sProviderId
	 * @param string $sProvider
	 * @throws \Exception
	 * @return \ZF2User\Entity\UserEntity|NULL
	 */
	public function getUser($sProviderId, $sProvider){
		if(!is_string($sProviderId) || !is_string($sProvider))throw new \Exception('ProviderId and/or Provider are not strings');
		$oUserProvider = $this->select(array())->current();
		if(!($oUserProvider = $this->select(array(
			'provider_id' => $sProviderId,
			'provider' => $sProvider
		))->current()) instanceof \ZF2User\Entity\UserProviderEntity)return null;
		elseif(($oUser = $this->userModel->select(array(
			'user_id' => $oUserProvider->getUserId()
		))->current()) instanceof \ZF2User\Entity\UserEntity)return $oUser;
		else throw new \Exception('Unknown User id : '.$oUserProvider->getUserId());
	}

	/**
	 * @param array $aUserProviderInfos
	 * @throws \Exception
	 * @return \ZF2User\Model\UserProviderModel
	 */
	public function create(array $aUserProviderInfos){
		//Check values
		if(!isset($aUserProviderInfos['provider_name'],$aUserProviderInfos['provider_id'],$aUserProviderInfos['user_id']))throw new \Exception('Infos for creating user provider infos are invalid');
		if(!$this->insert(array_intersect_key($aUserProviderInfos, array_flip(array('provider_name','provider_id','user_id')))))throw new \Exception('An error occurred when creating a new user provider');
		return $this;
	}
}
