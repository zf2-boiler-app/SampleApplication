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
	 * Constructor
	 */
	private function __construct(){
		$this->getSharedManager()->attach('Zend\Mvc\Application', \Zend\Mvc\MvcEvent::EVENT_RENDER,array($this,'buildLayoutTemplate'));
	}

	/**
	 * Instantiate a Templating service
	 * @param array|Traversable $oOptions
	 * @throws \Exception
	 * @return \Templating\Service\TemplatingService
	 */
	public static function factory($oOptions){
		if($oOptions instanceof \Traversable)$oOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($oOptions);
		elseif(!is_array($oOptions))throw new \Exception(__METHOD__.' expects an array or Traversable object; received "'.(is_object($oOptions)?get_class($oOptions):gettype($oOptions)).'"');
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
	 * @throws \Exception
	 * @return \Templating\Service\TemplatingConfiguration
	 */
	public function getConfiguration(){
		if($this->configuration instanceof \Templating\Service\TemplatingConfiguration)return $this->configuration;
		throw new \Exception('Configuration is undefined');
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
	 * Define layout template
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 * @return \Templating\Service\TemplatingService
	 */
	public function buildLayoutTemplate(\Zend\Mvc\MvcEvent $oEvent){
		if($oEvent->getRequest()->isXmlHttpRequest())return $this;

		$sModule = $oEvent->getControllerClass();
		$oTemplate = $this->getConfiguration()->getTemplateMapForModule($sModule);

		//Define layout
		$oLayoutView = $this->setChildrenToView(new \Zend\View\Model\ViewModel(),$oTemplate->getChildren());

		//Set header view
		$oFooterView = new \Zend\View\Model\ViewModel();
		$oEvent->getViewModel()->addChild($oFooterView->setTemplate('footer/footer'),'footer');

		$oEvent->getViewModel()->addChild(
			$oLayoutView->setTemplate($oTemplate->getConfiguration()->getLayout()),
			'specialLayout'
		);
		return $this;
	}

	/**
	 * @param \Zend\View\Model\ViewModel $oParentView
	 * @param array $aChildren
	 * @return \Zend\View\Model\ViewModel
	 */
	protected function setChildrenToView(\Zend\View\Model\ViewModel $oParentView, array $aChildren){
		foreach($aChildren as $sChildrenName => $sChildrenView){
			$oChildrenView = new \Zend\View\Model\ViewModel();
			if(is_string($sChildrenView))$oChildrenView->setTemplate($sChildrenView);
			elseif($sChildrenView instanceof \Templating\Service\Template\Template)$oChildrenView = $this->setChildrenToView(
				$oChildrenView->setTemplate($sChildrenView->getConfiguration()->getLayout()),
				$sChildrenView->getChildren()
			);
			$oParentView->addChild($oChildrenView,$sChildrenName);
		}
		return $oParentView;
	}
}