<?php
namespace Templating\Service;
class TemplatingService implements \Zend\EventManager\SharedEventManagerAwareInterface{
	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;

	/**
	 * Constructor
	 */
	private function __construct(){
		$this->getSharedManager()->attach('Zend\Mvc\Application', \Zend\Mvc\MvcEvent::EVENT_RENDER,array($this,'buildLayoutTemplate'));
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
		return $this;
	}
}