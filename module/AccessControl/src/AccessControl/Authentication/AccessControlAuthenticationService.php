<?php
namespace AccessControl\Authentication;
class AccessControlAuthenticationService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	const AUTH_RESULT_AUTH_ACCESS_STATE_PENDING = -1;
	const AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG = 0;
	const AUTH_RESULT_VALID = 1;

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @var \Zend\Authentication\AuthenticationService
	 */
	protected $authenticationService;

	/**
	 * @var array
	 */
	protected $adapters = array();

	/**
	 * @var \Zend\Authentication\Storage\StorageInterface
	 */
	protected $storage;

	/**
	 * Constructor
	 * @param array $aAdapters : (optionnal)
	 */
	public function __construct(\Zend\Authentication\Storage\StorageInterface $oStorage = null, array $aAdapters = null){
		if($aAdapters)$this->setAdapters($aAdapters);

	}

	/**
	 * Instantiate a UserAuthenticationService
	 * @param array|Traversable $aConfiguration
	 * @throws \Exception
	 */
	public static function factory($aConfiguration,\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		if($aConfiguration instanceof \Traversable)$aConfiguration = \Zend\Stdlib\ArrayUtils::iteratorToArray($aConfiguration);
		elseif(!is_array($aConfiguration))throw new \Exception(__METHOD__.' expects an array or Traversable object; received "'.(is_object($aConfiguration)?get_class($aConfiguration):gettype($aConfiguration)).'"');
		if(isset($aConfiguration['storage'])){
			if(!($aConfiguration['storage'] instanceof \Zend\Authentication\Storage\StorageInterface)){
				if(!is_string($aConfiguration['storage']))throw new\Exception(sprintf(
					'Storage configuration expects \Zend\Authentication\Storage\StorageInterface or string, "%s" given',
					is_object($aConfiguration['storage'])?get_class($aConfiguration['storage']):gettype($aConfiguration['storage'])
				));
				if($oServiceLocator->has($aConfiguration['storage']))$aConfiguration['storage'] = $oServiceLocator->get($aConfiguration['storage']);
				elseif(class_exists($aConfiguration['storage']))$aConfiguration['storage'] = new $aConfiguration['storage']();
				else throw new \Exception($aConfiguration['storage'].' is not an available service or an existing class');
			}

		}
		else $aConfiguration['storage'] = null;

		$oUserAuthenticationService = new static($aConfiguration['storage'],isset($aConfiguration['adapters'])?$aConfiguration['adapters']:array());
		return $oUserAuthenticationService->setServiceLocator($oServiceLocator);
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \User\Service\UserAccountService
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$this->serviceLocator = $oServiceLocator;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceLocator(){
		if($this->serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorInterface)return $this->serviceLocator;
		throw new \Exception('Service Locator is undefined');
	}

	/**
	 * @param array $aAdapters
	 * @throws \Exception
	 * @return \User\Service\UserAccountService
	 */
	public function setAdapters(array $aAdapters){
		if(empty($aAdapters))throw new \Exception('setAdapters expects not empty array');
		foreach($aAdapters as $sAdapterName => $oAdapter){
			if(is_array($sAdapterName)){
				if(!empty($oAdapter['name']))$sAdapterName = $oAdapter['name'];
				if(isset($oAdapter['adapter']))$oAdapter = $oAdapter['adapter'];
			}
			$this->setAdapter($sAdapterName, $oAdapter);
		}
		return $this;
	}

	/**
	 * @param string $sAdapterName
	 * @param \User\Authentication\Adapter\AuthenticationAdapterInterface|string $oAdapter
	 * @throws \Exception
	 * @return \User\Service\UserAccountService
	 */
	protected function setAdapter($sAdapterName, $oAdapter){
		if(!is_string($sAdapterName))throw new \Exception('Adapter\'s name expects string, '.gettype($sAdapterName));
		if($oAdapter instanceof \User\Authentication\Adapter\AuthenticationAdapterInterface)$this->adapters[$sAdapterName] = $oAdapter;
		elseif(is_string($oAdapter))$this->adapters[$sAdapterName] = $oAdapter;
		else throw new \Exception(sprintf(
			'Adapter expects \User\Authentication\Adapter\AuthenticationAdapterInterface or string, "%s" given',
			is_object($oAdapter)?get_class($oAdapter):gettype($oAdapter)
		));
		return $this;
	}

	/**
	 * @param string $sAdapterName
	 * @throws \Exception
	 * @return \User\Authentication\Adapter\AuthenticationAdapterInterface
	 */
	public function getAdapter($sAdapterName){
		if(!is_string($sAdapterName))throw new \Exception('Adapter\'s name expects string, '.gettype($sAdapterName));
		if(!isset($this->adapters[$sAdapterName]))throw new \Exception('Adapter "'.$sAdapterName.'" is undefined');
		if(!($this->adapters[$sAdapterName] instanceof \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface)){
			if(!is_string($this->adapters[$sAdapterName]))throw new \InvalidArgumentException(sprintf(
				'Adapter "%s" expects \AccessControl\Authentication\Adapter\AuthenticationAdapterInterface or string, "%s" given',
				$sAdapterName,is_object($this->adapters[$sAdapterName])?get_class($this->adapters[$sAdapterName]):gettype($sAdapterName)
			));
			if($this->getServiceLocator()->has($this->adapters[$sAdapterName]))$this->adapters[$sAdapterName] = $this->getServiceLocator()->get($this->adapters[$sAdapterName]);
			elseif(class_exists($this->adapters[$sAdapterName]))$this->adapters[$sAdapterName] = new $this->adapters[$sAdapterName]();
			else throw new \Exception($this->adapters[$sAdapterName].' is not an available service or an existing class');
		}
		return $this->adapters[$sAdapterName];
	}

	/**
	 * @return \Zend\Authentication\AuthenticationService
	 */
	public function getAuthenticationService(){
		return $this->authenticationService instanceof \Zend\Authentication\AuthenticationService
			?$this->authenticationService
			:$this->authenticationService = new \Zend\Authentication\AuthenticationService($this->storage);
	}

	/**
	 * @param string $sAdapter
	 */
	public function authenticate($sAdapterName){
		if(!is_string($sAdapterName))throw new \Exception('Adapter\'s name expects string, '.gettype($sAdapterName));

		//Performs adapter initialization
		$oAdapter = $this->getAdapter($sAdapterName);
		if(is_callable(array($oAdapter,'postAuthenticate')))call_user_func_array(
			array($this->getAdapter($sAdapterName),'postAuthenticate'),
			array_slice(func_get_args(),1)
		);

		$oAuthResult = $this->getAuthenticationService()->authenticate($oAdapter);
		if($oAuthResult->isValid()){
			//Check user's state
			$aUserStateInfos = $oAdapter->getResultRowObject(array('user_id','user_state'));
			$iUserId = (int)$aUserStateInfos->user_id;
			$sUserState = $aUserStateInfos->user_state;
		}
		else switch($oAuthResult->getCode()){
			case \Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
			case \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS:
			case \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID:
				return self::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG;
			case \Zend\Authentication\Result::FAILURE_UNCATEGORIZED:
			case \Zend\Authentication\Result::FAILURE:
				if($aMessages = $oAuthResult->getMessages()){
					$sReturn = '';
					$oTranslator = $this->getServiceLocator('translator');
					foreach($aMessages as $sMessage){
						if($sReturn)$sReturn .= ', ';
						$sReturn .= $oTranslator->translate($sMessage);
					}
					return $sReturn;
				}
			default:
				throw new \Exception('Unknown result failure code : '.$oAuthResult->getCode());
		}

		//Authentication is valid, check user state
		if(!isset($iUserId,$sUserState))throw new \Exception('User\'s id or user\'s state are undefined');

		if($sUserState === \User\Model\UserModel::USER_STATUS_ACTIVE){
			//Store user id
			$this->getAuthenticationService()->getStorage()->write($iUserId);
			return self::AUTH_RESULT_VALID;
		}
		else return self::AUTH_RESULT_AUTH_ACCESS_STATE_PENDING;
	}

	/**
	 * @return boolean
	 */
	public function hasIdentity(){
		return $this->getAuthenticationService()->hasIdentity();
	}

	/**
	 * @throws \Exception
	 * @return mixed
	 */
	public function getIdentity(){
		if($this->hasIdentity())return $this->getAuthenticationService()->getIdentity();
		throw new \Exception('There is no stored identity');
	}

	/**
	 * @throws \Exception
	 * @return \User\Authentication\UserAuthenticationService
	 */
	public function clearIdentity(){
		if(!$this->hasIdentity())throw new \Exception('There is no stored identity');
		//Clear auth storage
		$this->getAuthenticationService()->clearIdentity();

		//Clear adapter storage
		$oAdapter = $this->getAuthenticationService()->getAdapter();
		if(is_callable(array($oAdapter,'clearIdentity')))$oAdapter->clearIdentity();
		return $this;
	}
}