<?php
namespace Templating\Service;
class TemplatingService implements \Zend\EventManager\SharedEventManagerAwareInterface{
	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;

	/**
	 * @var \Templating\Service\TemplatingConfiguration
	 */
	protected $configuration;

	/**
	 * @var \Zend\Mvc\MvcEvent
	 */
	protected $currentEvent;

	/**
	 * @var \Zend\View\Resolver\ResolverInterface
	 */
	protected $templateResolver;

	/**
	 * Constructor
	 */
	private function __construct(){
		$this->getSharedManager()->attach('Zend\Mvc\Application', \Zend\Mvc\MvcEvent::EVENT_RENDER,array($this,'buildLayoutTemplate'));
	}

	/**
	 * Instantiate a Templating service
	 * @param array|Traversable $oOptions
	 * @throws \InvalidArgumentException
	 * @return \Templating\Service\TemplatingService
	 */
	public static function factory($oOptions){
		if($oOptions instanceof \Traversable)$oOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($oOptions);
		elseif(!is_array($oOptions))throw new \InvalidArgumentException(__METHOD__.' expects an array or Traversable object; received "'.(is_object($oOptions)?get_class($oOptions):gettype($oOptions)).'"');
		$oTemplatingService = new static();
		return $oTemplatingService->setConfiguration(new \Templating\Service\TemplatingConfiguration($oOptions));
	}

	/**
	 * @param \Templating\Service\TemplatingConfiguration $oConfiguration
	 * @return \Templating\Service\TemplatingService
	 */
	public function setConfiguration(\Templating\Service\TemplatingConfiguration $oConfiguration){
		$this->configuration = $oConfiguration;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \Templating\Service\TemplatingConfiguration
	 */
	public function getConfiguration(){
		if($this->configuration instanceof \Templating\Service\TemplatingConfiguration)return $this->configuration;
		throw new \LogicException('Configuration is undefined');
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
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 * @return \Templating\Service\TemplatingService
	 */
	protected function setCurrentEvent(\Zend\Mvc\MvcEvent $oEvent){
		$this->currentEvent = $oEvent;
		//Set template resolver
		$this->setTemplateResolver($oEvent->getApplication()->getServiceManager()->get('ViewRenderer')->resolver());
		return $this;
	}

	/**
	 * @return \Templating\Service\TemplatingService
	 */
	protected function unsetCurrentEvent(){
		$this->currentEvent = null;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \Zend\Mvc\MvcEvent
	 */
	protected function getCurrentEvent(){
		if($this->currentEvent instanceof \Zend\Mvc\MvcEvent)return $this->currentEvent;
		throw new \LogicException('Current event is undefined');
	}

	/**
	 * @param \Zend\View\Resolver\ResolverInterface $oTemplateResolver
	 * @return \Templating\Service\TemplatingService
	 */
	protected function setTemplateResolver(\Zend\View\Resolver\ResolverInterface $oTemplateResolver){
		$this->templateResolver = $oTemplateResolver;
		return $this;
	}

	/**
	 * @throws \LogicException
	 * @return \Zend\View\Resolver\ResolverInterface
	 */
	protected function getTemplateResolver(){
		if($this->templateResolver instanceof \Zend\View\Resolver\ResolverInterface)return $this->templateResolver;
		throw new \LogicException('Template Resolver is undefined');
	}

	/**
	 * Define layout template
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 * @return \Templating\Service\TemplatingService
	 */
	public function buildLayoutTemplate(\Zend\Mvc\MvcEvent $oEvent){
		$oRequest = $oEvent->getRequest();
		if(!($oRequest instanceof \Zend\Http\Request) || $oRequest->isXmlHttpRequest() || (
    		($oView = $oEvent->getResult()) instanceof \Zend\View\Model\ModelInterface
    		&& $oView->terminate()
    	))return $this;

		//Define current event
		$this->setCurrentEvent($oEvent);

		//Define module Name

		/* @var $oRouter \Zend\Mvc\Router\RouteMatch */
		$oRouter = $this->getCurrentEvent()->getRouteMatch();
		$sModule = null;
		if($oRouter instanceof \Zend\Mvc\Router\RouteMatch)$sModule = current(explode('\\',$oRouter->getParam('controller')));
		if(!$sModule)$sModule = \Templating\Service\TemplatingConfiguration::DEFAULT_TEMPLATE_MAP;

		try{
			//Retrieve template for module
			$oTemplate = $this->getConfiguration()->hasTemplateMapForModule($sModule)
				?$this->getConfiguration()->getTemplateMapForModule($sModule)
				:$this->getConfiguration()->getTemplateMapForModule(\Templating\Service\TemplatingConfiguration::DEFAULT_TEMPLATE_MAP);

			//Set layout template and add its children
			$sTemplate = $oTemplate->getConfiguration()->getTemplate();
			if(is_callable($sTemplate))$sTemplate = $sTemplate($this->getCurrentEvent());
			$this->setChildrenToView(
				$oEvent->getViewModel()->setTemplate($sTemplate),
				$oTemplate->getChildren()
			);
		}
		catch(\Exception $oException){
			throw new \RuntimeException('Error occured during building layout template process');
		}
		//Reset current event
		return $this->unsetCurrentEvent();
	}

	/**
	 * @param \Zend\View\Model\ViewModel $oParentView
	 * @param array $aChildren
	 * @return \Zend\View\Model\ViewModel
	 */
	protected function setChildrenToView(\Zend\View\Model\ViewModel $oParentView, array $aChildren){
		$oTemplateResolver = $this->getTemplateResolver();
		foreach($aChildren as $sChildrenName => $oChildrenTemplate){
			$sTemplate = $oChildrenTemplate->getConfiguration()->getTemplate();
			if(is_callable($sTemplate))$sTemplate = $sTemplate($this->getCurrentEvent());

			//Check if template exists
			if(!$oTemplateResolver->resolve($sTemplate))throw new \UnexpectedValueException('Template name "'.$sTemplate.'" could not be resolved');
			$oParentView->addChild(
				$this->setChildrenToView(
					new \Zend\View\Model\ViewModel(),
					$oChildrenTemplate->getChildren()
				)->setTemplate($sTemplate),
				$sChildrenName
			);
		}
		return $oParentView;
	}
}