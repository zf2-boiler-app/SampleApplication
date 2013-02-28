<?php
namespace AccessControl\Authentication\Adapter;
class AuthenticationDoctrineAdapter extends \Zend\Authentication\Adapter\AbstractAdapter implements \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface{

	/**
	 * @var \AccessControl\Service\AccessControlService
	 */
	protected $accessControlService;

	/**
	 * @var array
	 */
	protected $resultRow;

	/**
	 * Constructor
	 * @param \AccessControl\Service\AccessControlService $oAccessControlService
	 */
	public function __construct(\AccessControl\Service\AccessControlService $oAccessControlService = null){
		if($oAccessControlService)$this->setAccessControlService($oAccessControlService);
	}

	/**
	 * @param \AccessControl\Repository\AuthAccessRepository $oAccessControlService
	 * @return \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter
	 */
	public function setAccessControlService(\AccessControl\Service\AccessControlService $oAccessControlService){
		$this->accessControlService = $oAccessControlService;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function getAccessControlService(){
		if(!($this->accessControlService instanceof \AccessControl\Service\AccessControlService))throw new \LogicException('AccessControl service is undefined');
		return $this->accessControlService;
	}

	/**
	 * @param string $sIdentity
	 * @param string $sCredential
	 * @throws \InvalidArgumentException
	 * @return \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter
	 */
	public function postAuthenticate($sIdentity,$sCredential){
		if(!is_string($sIdentity) || !is_string($sCredential))throw new \InvalidArgumentException(sprintf(
			'Identity (%s) and/or credential(%s) are not strings',
			gettype($sIdentity),gettype($sCredential)
		));
		return $this->setIdentity($sIdentity)->setCredential($sCredential);
	}

	/**
	 * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
	 * @return \Zend\Authentication\Result
	 */
	public function authenticate(){
		//Reset previous identity datas
		$this->resultRow = null;

		//Retrieve AuthAccess from provided identity
		if(!($oAuthAccess = $this->getAccessControlService()->getAuthAccessFromIdentity($this->getIdentity())))return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND,null);

		//Verify credential

		//Crypter
		$oBCrypt = new \Zend\Crypt\Password\Bcrypt();
		if(!$oBCrypt->verify(
			md5($this->getCredential()),
			$oAuthAccess->getAuthAccessCredential()
		))return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID,null);

		$this->resultRow = array(
			'user_id' => $oAuthAccess->getAuthAccessUser()->getUserId(),
			'user_state' => $oAuthAccess->getAuthAccessState()
		);
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
		$oReturnObject = new \stdClass();

		if(null !== $aReturnColumns){
			$aAvailableColumns = array_keys($this->resultRow);
			foreach((array) $aReturnColumns as $sReturnColumn){
				if(in_array($sReturnColumn, $aAvailableColumns))$oReturnObject->{$sReturnColumn} = $this->resultRow[$sReturnColumn];
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
}