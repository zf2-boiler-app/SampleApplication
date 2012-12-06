<?php
namespace Logger\Service;
class LoggerService implements \Zend\EventManager\SharedEventManagerAwareInterface{
	const LOG_TYPE_DEFAULT = 'default';
	const LOG_TYPE_MVC_ACTION = 'mvc_action';
	const LOG_TYPE_ENTITY = 'entity';
	const LOG_TYPE_ERROR = 'error';
	
	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;
	
	/**
	 * Plugin manager for logging adapters.
	 * @var \Logger\Service\AdapterPluginManager
	 */
	protected $pluginManager;
	
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
		self::$currentId = uniqid(time());
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
		if(!($this->sharedEventManager instanceof \Zend\EventManager\SharedEventManagerInterface))$this->sharedEventManager = \Zend\EventManager\StaticEventManager::getInstance();
		return $this->getSharedManager();
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
	 * @param  array|Traversable $oOptions
	 * @throws \Exception
	 * @return \Logger\Service\LoggerService
	 */
	public static function factory($oOptions){
		if($oOptions instanceof Traversable)$oOptions = ArrayUtils::iteratorToArray($oOptions);
		elseif(!is_array($oOptions))throw new \Exception(__METHOD__.' expects an array or Traversable object; received "'.(is_object($options)?get_class($options):gettype($options)).'"');
		$oLogger = new static();
		if(!isset($oOptions['adapters']))throw new \Exception('Adapters option is undefined');
		if(is_array($oOptions['adapters']))foreach($oOptions['adapters'] as $sType => $oAdapter){			
			if(is_string($sType))$this->setAdapters($sType,$oAdapter);
			else $this->setAdapters($oAdapter);
		}
		else $this->setAdapter(self::LOG_TYPE_DEFAULT,$oOptions['adapters']);
		return $oLogger;
	}
	
	/**
	 * Set the plugin manager for logging adapters
	 * @param \Logger\Service\AdapterPluginManager $oPluginManager
	 * @return \Logger\Service\LoggerService
	 */
	public function setPluginManager(\Logger\Service\AdapterPluginManager $oPluginManager){
		$this->pluginManager = $oPluginManager;
		return $this;
	}
	
	/**
	 * Retrieve the plugin manager for translation loaders.
	 * Lazy loads an instance if none currently set.
	 * @return \Logger\Service\AdapterPluginManager
	 */
	public function getPluginManager(){
		if(!$this->pluginManager instanceof \Logger\Service\AdapterPluginManager)$this->setPluginManager(new \Logger\Service\AdapterPluginManager());
		return $this->pluginManager;
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
			if(class_exists($oAdapter)){
				$oAdapter = new $oAdapter();
				if(!($oAdapter instanceof \Logger\Service\Adapter\LogAdapterInterface)) throw new \Exception('Log adapter expects to be instance of \Logger\Service\Adapter\LogAdapterInterface; recieved "'.get_class($oAdapter).'"');
			}
			else $oAdapter = $this->getPluginManager()->get($oAdapter);
			$this->adapters[$sLogType] = $oAdapter;
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
		if(isset($this->adapters[$sLogType]))$this->adapters[$sLogType];
		elseif(isset($this->adapters[self::LOG_TYPE_DEFAULT]))return $this->adapters[self::LOG_TYPE_DEFAULT];
		else throw new \Exception('"'.$sLogType.'" Log adapters is undefined. Define at least "default" Log adapter');
	}
	
	protected function logMvcAction(\Zend\Mvc\MvcEvent $oEvent){
		$this->getAdapter(self::LOG_TYPE_MVC_ACTION)->log(self::$currentId,time(),$oEvent->getRouteMatch());
		return $this;
	}
	
	protected function logEntity(\Zend\EventManager\Event $oEvent){
		$this->getAdapter(self::LOG_TYPE_ENTITY)->log(self::$currentId,time(),$oEvent->getParam('entity'));
		return $this;		
	}
	
	protected function logError(\Zend\Mvc\MvcEvent $oEvent){
		return $this->getAdapter(self::LOG_TYPE_ERROR)->log(self::$currentId,time(),$oEvent->getParam('error'),$oEvent->getParam('exception'));
	}
}