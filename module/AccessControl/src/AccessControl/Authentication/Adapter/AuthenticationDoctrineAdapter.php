<?php
namespace AccessControl\Authentication\Adapter;
class AuthenticationDoctrineAdapter extends \Zend\Authentication\Adapter\AbstractAdapter implements \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface{

	/**
	 * @var \User\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @param \User\Repository\UserRepository $oRepository
	 * @return \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter
	 */
	public function setUserRepository(\User\Repository\UserRepository $oRepository){
		$this->userRepository = $oRepository;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \User\Repository\UserRepository
	 */
	public function getUserRepository(){
		if(!($this->userRepository instanceof \User\Repository\UserRepository))throw new \LogicException('User repository is undefined');
		return $this->userRepository;
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

		//Try retrieving existing user for the giving identity (email)
		if($oUser = $this->getUserRepository()->findOneBy(array(
			'user_email' => $this->getIdentity(),
			'user_password' => $this->getCredential()
		))){
			$this->resultRow = array(
				'user_id' => $oUser->getUserId(),
				'user_state' => $oUser->getUserState()
			);
			return new \Zend\Authentication\Result(\Zend\Authentication\Result::SUCCESS,$this->resultRow['user_id']);
		}
		return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID);
	}
}