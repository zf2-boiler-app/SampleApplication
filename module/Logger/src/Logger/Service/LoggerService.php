<?php
namespace Logger\Service;
class LoggerService implements \Zend\EventManager\SharedEventManagerAwareInterface{
	const LOG_EVENT_CREATE_ENTITY = 'CREATE_ENTITY';
	const LOG_EVENT_UPDATE_ENTITY = 'UPDATE_ENTITY';
	const LOG_EVENT_DELETE_ENTITY = 'DELETE_ENTITY';

	const LOG_TYPE_DEFAULT = 'default';
	const LOG_TYPE_MVC_ACTION = 'mvc_action';
	const LOG_TYPE_ENTITY = 'entity';
	const LOG_TYPE_ERROR = 'error';

	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;

	/**
	 * Adapters
	 * @var array
	 */
	protected $adapters = array();

	/**
	 * @var string
	 */
	protected static $currentId;

	/**
	 * Constructor
	 */
	private function __construct(){
		//Define current id
		self::$currentId = uniqid(time());

		//Mvc
		$this->getSharedManager()->attach('Zend\Mvc\Application', \Zend\Mvc\MvcEvent::EVENT_ROUTE,array($this,'logMvcAction'));
		$this->getSharedManager()->attach('Zend\Mvc\Application', array(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR),array($this,'logError'));
	}

	/**
	 * Destructor
	 */
	public function __destruct(){
		//Stop logger
		$this->getAdapter(self::LOG_TYPE_MVC_ACTION)->completed(self::$currentId,new \DateTime());
		self::$currentId = null;
	}

	/**
	 * Inject a SharedEventManager instance
	 * @param \Zend\EventManager\SharedEventManagerInterface $oSharedEventManager
	 * @return \Logger\Service\LoggerService
	 */
	public function setSharedManager(\Zend\EventManager\SharedEventManagerInterface $oSharedEventManager){
		$this->sharedEventManager = $oSharedEventManager;
		return $this;
	}

	/**
	 * Get shared collections container
	 * @return \Zend\EventManager\SharedEventManagerInterface
	 */
	public function getSharedManager(){
		return $this->sharedEventManager instanceof \Zend\EventManager\SharedEventManagerInterface
			?$this->sharedEventManager
			:$this->sharedEventManager = \Zend\EventManager\StaticEventManager::getInstance();
	}

	/**
	 * Remove any shared collections
	 * @return \Logger\Service\LoggerService
	*/
	public function unsetSharedManager(){
		$this->sharedEventManager = null;
		return $this;
	}

	/**
	 * Instantiate a logger
	 * @param array|Traversable $oOptions
	 * @throws \Exception
	 * @return \Logger\Service\LoggerService
	 */
	public static function factory($oOptions){
		if($oOptions instanceof \Traversable)$oOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($oOptions);
		elseif(!is_array($oOptions))throw new \Exception(__METHOD__.' expects an array or Traversable object; received "'.(is_object($oOptions)?get_class($oOptions):gettype($oOptions)).'"');
		$oLogger = new static();
		if(!isset($oOptions['adapters']))throw new \Exception('Adapters option is undefined');
		if(is_array($oOptions['adapters']))foreach($oOptions['adapters'] as $sType => $oAdapter){
			if(is_string($sType))$oLogger->setAdapters($sType,$oAdapter);
			else $oLogger->setAdapters($oAdapter);
		}
		else $oLogger->setAdapter(self::LOG_TYPE_DEFAULT,$oOptions['adapters']);
		return $oLogger;
	}

	/**
	 * Check if log type exists
	 * @param string $sLogType
	 * @return boolean
	 */
	protected static function logTypeExists($sLogType){
		switch($sLogType){
			case self::LOG_TYPE_MVC_ACTION:
			case self::LOG_TYPE_ERROR:
			case self::LOG_TYPE_ENTITY:
			case self::LOG_TYPE_DEFAULT:
				return true;
			default:
				return false;
		}
	}

	/**
	 * Set adapter
	 * @param string|array $sLogType
	 * @param string|\Logger\Service\Adapter\LogAdapterInterface $oAdapter
	 * @throws \Exception
	 * @return \Logger\Service\LoggerService
	 */
	public function setAdapter($sLogType,$oAdapter = null){
		if(is_array($sLogType)){
			if(isset($sLogType['type'],$sLogType['adapter'])){
				$oAdapter = $sLogType['adapter'];
				$sLogType = $sLogType['type'];
			}
			else throw new \Exception('Adapter config  expects an array with "type" and "adapter" keys.');
		}

		if(!self::logTypeExists($sLogType))throw new \Exception('Log type expects to be defined; recieved "'.(is_string($sLogType)?$sLogType:gettype($sLogType)).'"');
		if($oAdapter instanceof \Logger\Service\Adapter\LogAdapterInterface)$this->adapters[$sLogType] = $oAdapter;
		elseif(is_string($oAdapter)){
			if(!class_exists($oAdapter))throw new \Exception('Undefined class name "'.$oAdapter.'"');
			elseif(!($oAdapter instanceof \Logger\Service\Adapter\LogAdapterInterface))throw new \Exception(sprintf(
				'Log adapter expects to be instance of \Logger\Service\Adapter\LogAdapterInterface; recieved "%s"',
				get_class($oAdapter)
			));
			$this->adapters[$sLogType] = new $oAdapter();
		}
		else throw new \Exception('Log adapter expects a string or be instance of \Logger\Service\Adapter\LogAdapterInterface; recieved "'.(is_object($oAdapter)?get_class($oAdapter):gettype($oAdapter)).'"');
		return $this;
	}

	/**
	 * Retrieve log adapater for log type, default if not exist
	 * @param string $sLogType
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\LogAdapterInterface
	 */
	protected function getAdapter($sLogType){
		if(!is_string($sLogType))throw new \Exception('Log type must be string; recieved "'.gettype($sLogType).'"');
		elseif(!self::logTypeExists($sLogType))throw new \Exception('Log type "'.$sLogType.'" is undefined');
		elseif(isset($this->adapters[$sLogType]))return $this->adapters[$sLogType];
		elseif(isset($this->adapters[self::LOG_TYPE_DEFAULT]))return $this->adapters[self::LOG_TYPE_DEFAULT];
		else throw new \Exception('"'.$sLogType.'" Log adapters is undefined. Define at least "default" Log adapter');
	}

	/**
	 * Start loggin process
	 * @param \Zend\Http\Request $oRequest
	 * @return \Logger\Service\LoggerService
	 */
	public function start(\Zend\Http\Request $oRequest){
		self::$currentId = $this->getAdapter(self::LOG_TYPE_MVC_ACTION)->started(self::$currentId,new \DateTime(),$oRequest)->getLogId();
		return $this;
	}

	/**
	 * Log Mvc actions
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 * @return \Logger\Service\LoggerService
	 */
	public function logMvcAction(\Zend\Mvc\MvcEvent $oEvent){
		self::$currentId = $this->getAdapter(self::LOG_TYPE_MVC_ACTION)->log(self::$currentId,new \DateTime(),$oEvent->getRouteMatch())->getLogId();
		return $this;
	}

	/**
	 * Log entities creates
	 * @param \Zend\EventManager\Event $oEvent
	 * @return \Logger\Service\LoggerService
	 */
	public function logCreateEntity(\Zend\EventManager\EventInterface $oEvent){
		$aParams = $oEvent->getParams();
		if(!isset($aParams['insertedId'],$aParams['table'],$aParams['primaryKey']))throw new \Exception(sprintf(
			'logCreateEntity Event expects insertedId, table & primaryKey params. "%s" given.',
			join(', ', array_keys($aParams))
		));
		self::$currentId = $this->getAdapter(self::LOG_TYPE_ENTITY)->log(
			self::$currentId,
			new \DateTime(),
			$aParams['insertedId'],
			$aParams['table'],
			$aParams['primaryKey'],
			self::LOG_EVENT_CREATE_ENTITY
		)->getLogId();
		return $this;
	}

	/**
	 * Log entities updates
	 * @param \Zend\EventManager\Event $oEvent
	 * @return \Logger\Service\LoggerService
	 */
	public function logUpdateEntity(\Zend\EventManager\EventInterface $oEvent){
		$aParams = $oEvent->getParams();
		if(!isset($aParams['updatedIds'],$aParams['table'],$aParams['primaryKey']))throw new \Exception(sprintf(
			'logUpdateEntity Event expects updatedIds, table & primaryKey params. "%s" given.',
			join(', ', array_keys($aParams))
		));
		self::$currentId = $this->getAdapter(self::LOG_TYPE_ENTITY)->log(
			self::$currentId,
			new \DateTime(),
			$aParams['updatedIds'],
			$aParams['table'],
			$aParams['primaryKey'],
			self::LOG_EVENT_UPDATE_ENTITY
		)->getLogId();
		return $this;
	}

	/**
	 * Log entities deletes
	 * @param \Zend\EventManager\Event $oEvent
	 * @return \Logger\Service\LoggerService
	 */
	public function logDeleteEntity(\Zend\EventManager\EventInterface $oEvent){
		$aParams = $oEvent->getParams();
		if(!isset($aParams['deletedIds'],$aParams['table'],$aParams['primaryKey']))throw new \Exception(sprintf(
			'logDeleteEntity Event expects deletedIds, table & primaryKey params. "%s" given.',
			join(', ', array_keys($aParams))
		));
		self::$currentId = $this->getAdapter(self::LOG_TYPE_ENTITY)->log(
			self::$currentId,
			new \DateTime(),
			$aParams['deletedIds'],
			$aParams['table'],
			$aParams['primaryKey'],
			self::LOG_EVENT_DELETE_ENTITY
		)->getLogId();
		return $this;
	}

	/**
	 * Log errors
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 * @return \Logger\Service\LoggerService
	 */
	public function logError(\Zend\Mvc\MvcEvent $oEvent){
		if(!($oException = $oEvent->getParam('exception')) instanceof \Exception)$oException = new \Exception($oEvent->getError());
		self::$currentId = $this->getAdapter(self::LOG_TYPE_ERROR)->log(
			self::$currentId,
			new \DateTime(),
			$oException,
			$oEvent->getParam('error')
		)->getLogId();
		return $this;
	}
}