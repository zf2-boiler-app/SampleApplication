<?php
namespace AccessControl\Authentication\Adapter;
class AuthenticationDoctrineAdapter extends \Zend\Authentication\Adapter\AbstractAdapter implements \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface{

	/**
	 * @var \AccessControl\Repository\AuthAccessRepository
	 */
	protected $authAccessRepository;

	/**
	 * @param \AccessControl\Repository\AuthAccessRepository $oRepository
	 * @return \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter
	 */
	public function setAuthAccessRepository(\AccessControl\Repository\AuthAccessRepository $oRepository){
		$this->authAccessRepository = $oRepository;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \AccessControl\Repository\AuthAccessRepository
	 */
	public function getAuthAccessRepository(){
		if(!($this->authAccessRepository instanceof \AccessControl\Repository\AuthAccessRepository))throw new \LogicException('AuthAccess repository is undefined');
		return $this->authAccessRepository;
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

		$aAvailableIdentities = $this->getAuthAcessRepository()->getAvailableIdentities();
		$oAuthAccess = null;

		//Try retrieving existing AuthAccess for the giving identities
		while(!$oAuthAccess && $aAvailableIdentities){
			$sIdentityName = array_shift($aAvailableIdentities);
			if($sIdentityName === 'auth_access_email_identity' && !filter_var($sAuthAccessIdentity,FILTER_VALIDATE_EMAIL))continue;
			$oAuthAccess = $this->getAuthAcessRepository()->findOneBy(array(
				$sIdentityName => $this->getIdentity(),
				'auth_access_credential' => $this->getCredential()
			));
		}
		if(!$oAuthAccess)return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID);

		$this->resultRow = array(
			'user_id' => $oAuthAccess->getAuthAcessUser()->getUserId(),
			'user_state' => $oAuthAccess->getAuthAcessState()
		);
		return new \Zend\Authentication\Result(\Zend\Authentication\Result::SUCCESS,$this->resultRow['user_id']);
	}
}