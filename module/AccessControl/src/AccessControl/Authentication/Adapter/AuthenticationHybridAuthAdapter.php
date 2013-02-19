<?php
namespace AccessControl\Authentication\Adapter;
class AuthenticationHybridAuthAdapter implements \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface{
	const AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED = '';
	const AUTH_RESULT_HYBRID_AUTH_CANCELED = '';

	/**
	 * @var \User\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var \AccessControl\Repository\AuthProviderRepository
	 */
	protected $authProviderRepository;

	/**
	 * @var \Hybrid_Auth
	 */
	protected $hybridAuth;

	/**
	 * @var string
	 */
	protected $currentService;

	/**
	 * @var array
	 */
	protected $resultRow;

	/**
	 * Constructor
	 * @param \User\Repository\UserRepository $oUserRepository
	 * @param \AccessControl\Repository\AuthProviderRepository $oAuthProviderRepository
	 * @param \Hybrid_Auth $oHybridAuth
	 */
	public function __construct(
		\User\Repository\UserRepository $oUserRepository = null,
		\AccessControl\Repository\AuthProviderRepository $oAuthProviderRepository = null,
		\Hybrid_Auth $oHybridAuth = null
	){
		if($oUserRepository)$this->setUserRepository($oUserRepository);
		if($oAuthProviderRepository)$this->setAuthProviderRepository($oAuthProviderRepository);
		if($oHybridAuth)$this->setHybridAuth($oHybridAuth);
	}

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
	 * @param \AccessControl\Repository\AuthProviderRepository $oRepository
	 * @return \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter
	 */
	public function setAuthProviderRepository(\AccessControl\Repository\AuthProviderRepository $oRepository){
		$this->authProviderRepository = $oRepository;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \AccessControl\Repository\AuthProviderRepository
	 */
	public function getAuthProviderRepository(){
		if(!($this->authProviderRepository instanceof \AccessControl\Repository\AuthProviderRepository))throw new \LogicException('AuthProvider repository is undefined');
		return $this->authProviderRepository;
	}

	/**
	 * @param \Hybrid_Auth $oHybridAuth
	 * @return \AccessControl\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 */
	public function setHybridAuth(\Hybrid_Auth $oHybridAuth){
		$this->hybridAuth = $oHybridAuth;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return \Hybrid_Auth
	 */
	public function getHybridAuth(){
		if($this->hybridAuth instanceof \Hybrid_Auth)return $this->hybridAuth;
		throw new \Exception('HybridAuth is undefined');
	}

	/**
	 * @param string $sCurrentService
	 * @throws \InvalidArgumentException
	 * @return \AccessControl\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 */
	public function setCurrentService($sCurrentService){
		if(!is_string($sCurrentService))throw new \InvalidArgumentException('Service expects string, "'.gettype($sCurrentService).'" given');
		$this->currentService = $sCurrentService;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return string
	 */
	public function getCurrentService(){
		if(!is_string($this->currentService))throw new \LogicException('Service is undefined');
		return $this->currentService;
	}

	/**
	 * @param string $sCurrentService
	 * @return \AccessControl\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 */
	public function postAuthenticate($sCurrentService){
		if($sCurrentService)$this->setCurrentService($sCurrentService);
		return $this;
	}

	/**
	 * @see Hybrid_Auth::authenticate()
	 * @throws \UnexpectedValueException
	 * @return \Zend\Authentication\Result
	 */
	public function authenticate(){
		//Reset previous identity datas
		$this->resultRow = null;
		$this->getHybridAuth()->logoutAllProviders();

		try{
			$this->getHybridAuth()->authenticate($this->currentService);
			$oUserProfile = $this->getUserProfile();
		}
		catch(\Exception $oException){
			$this->getHybridAuth()->logout();
			$sMessage = null;
			switch($oException->getCode()){
				case 5 :
					$sMessage = self::AUTH_RESULT_HYBRID_AUTH_CANCELED;
				case 6 :
				case 7 :
					$sMessage = self::AUTH_RESULT_HYBRID_AUTH_USER_NOT_CONNECTED;
				default:
					throw new \UnexpectedValueException('Unexpected hybrid auth exception return code : '.$oException->getCode());
			}
			return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE,null,(array)$sMessage);
		}

		//Retrieve Auth provider entity & related User entity
		if(($oAuthProvider = $this->getAuthProviderRepository()->findOneBy(array(
			'provider_id' => $oUserProfile->identifier,
			'provider_name' => $sService
		))) && ($oUser = $oAuthProvider->getUser()))$this->resultRow = array(
			'user_id' => $oUser->getUserId(),
			'user_state' => $oUser->getUserState()
		);
		else{
			//Create user
			$oUser = new \User\Entity\UserEntity();
			$oUser = $this->getUserRepository()->create($oUser->setUserEmail($oUserProfile->email)
			->setUserState(\User\Repository\UserRepository::USER_STATUS_ACTIVE));

			//Link to auth provider
			$oAuthProvider = new \AccessControl\Entity\AuthProviderEntity();
			$this->getAuthProviderRepository()->create($oAuthProvider->setUser($oUser)->setProviderId($oUserProfile->identifier)->setProviderName($sService));

			$this->resultRow = array(
				'user_id' => $oUser->getUserId(),
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
		$oReturnObject = new \stdClass();

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
	 * @return \AccessControl\Authentication\Adapter\AuthenticationHybridAuthAdapter
	 */
	public function clearIdentity(){
		$this->logoutAllProviders();
		return $this;
	}
}