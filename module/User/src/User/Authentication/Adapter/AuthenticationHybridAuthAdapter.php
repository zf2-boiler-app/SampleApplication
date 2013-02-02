<?php
namespace User\Authentication\Adapter;
class AuthenticationHybridAuthAdapter extends \Hybrid_Auth implements \User\Authentication\Adapter\AuthenticationAdapterInterface{
	const AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED = '';
	const AUTH_RESULT_HYBRID_AUTH_CANCELED = '';

	/**
	 * @var string
	 */
	protected $currentService;

	/**
	 * @var array
	 */
	protected $resultRow;

	/**
	 * @return \User\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 * @throws \Exception
	 */
	public function initialize($sCurrentService){
		if(!is_string($sCurrentService))throw new \Exception('Service expects string, "'.gettype($sCurrentService).'" given');
		$this->currentService = $sCurrentService;
		return $this;
	}

	/**
	 * @see Hybrid_Auth::authenticate()
	 * @throws \Exception
	 * @return \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE
	 */
	public function authenticate(){
		if(!is_string($this->currentService))throw new \Exception('Current service expects string, "'.gettype($this->currentService).'" given');

		//Reset previous identity datas
		$this->resultRow = null;
		$this->logoutAllProviders();

		try{
			parent::authenticate($this->currentService);
			$oUserProfile = $this->getUserProfile();
		}
		catch(\Exception $oException){
			$this->logout();
			$sMessage = null;
			switch($oException->getCode()){
				case 5 :
					$sMessage = self::AUTH_RESULT_HYBRID_AUTH_CANCELED;
				case 6 :
				case 7 :
					$sMessage = self::AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED;
				default:
					throw new \Exception('Unexpected hybrid auth exception return code : '.$oException->getCode());
			}
			return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE,null,(array)$sMessage);
		}

		if(($oUser = $this->getServiceLocator()->get('UserProviderModel')->getUser($oUserProfile->identifier,$sService)) instanceof \User\Entity\UserEntity)$this->resultRow = array(
			'user_id' => $oUser->getUserId(),
			'user_state' => $oUser->getUserState()
		);
		else{
			//Create user
			$iUserId = $this->getServiceLocator()->get('UserModel')->create(array(
				'user_email' => $oUserProfile->email,
				'user_state' => \User\Model\UserModel::USER_STATUS_ACTIVE
			));

			//Link to user provider
			$this->getServiceLocator()->get('UserProviderModel')->create(array(
				'user_id' => $iUserId,
				'provider_id' => $oUserProfile->identifier,
				'provider_name' => $sService
			));

			$this->resultRow = array(
				'user_id' => $iUserId,
				'user_state' => \User\Model\UserModel::USER_STATUS_ACTIVE
			);
		}
		return new \Zend\Authentication\Result(\Zend\Authentication\Result::SUCCESS,$this->resultRow['user_id']);
	}

	/**
	 * Returns the result row as a stdClass object
	 * @param string|array $aReturnColumns
	 * @param string|array $aOmitColumns
	 * @return stdClass|boolean
	 */
	public function getResultRowObject($aReturnColumns = null, $aOmitColumns = null){
		if(!$this->resultRow)return false;
		$oReturnObject = new stdClass();

		if(null !== $aReturnColumns){
			$aAvailableColumns = array_keys($this->resultRow);
			foreach((array) $aReturnColumns as $sReturnColumn){
				if(in_array($sReturnColumn, $aAvailableColumns))$returnObject->{$sReturnColumn} = $this->resultRow[$sReturnColumn];
			}
			return $oReturnObject;

		}
		elseif(null !== $aOmitColumns){
			$aOmitColumns = (array)$aOmitColumns;
			foreach ($this->resultRow as $sResultColumn => $sResultValue) {
				if(!in_array($sResultColumn, $aOmitColumns))$oReturnObject->{$sResultColumn} = $sResultValue;
			}
			return $oReturnObject;

		}
		foreach($this->resultRow as $sResultColumn => $sResultValue){
			$oReturnObject->{$sResultColumn} = $sResultValue;
		}
		return $oReturnObject;
	}

	/**
	 * @return \User\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 */
	public function clearIdentity(){
		$this->logoutAllProviders();
		return $this;
	}
}