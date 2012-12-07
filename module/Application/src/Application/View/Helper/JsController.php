<?php
namespace Application\View\Helper;
class JsController extends \Zend\View\Helper\AbstractHelper implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

	/**
	 * @var \Zend\Mvc\Router\Http\RouteMatch
	 */
	private $routeMatch;

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;


	public function __construct($oRouteMatch = null){
		if($oRouteMatch instanceof \Zend\Mvc\Router\Http\RouteMatch)$this->routeMatch = $oRouteMatch;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \ZF2User\Service\UserService
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$this->serviceLocator = $oServiceLocator;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator(){
		if($this->serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorInterface)return $this->serviceLocator;
		throw new \Exception('Service Locator is undefined');
	}

	public function __invoke(){
		/* @var $oTranslator \Application\Translator\Translator */
		$oTranslator = $this->getServiceLocator()->getServiceLocator()->get('translator');
		$sControllerName = $this->routeMatch?str_ireplace('\\','',$this->routeMatch->getParam('controller')):'NoController';
		return $this->getServiceLocator()->get('inlineScript')->__invoke(\Zend\View\Helper\HeadScript::SCRIPT)->appendScript('
			var oControllerOptions = {
				\'locale\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($oTranslator->getLocale()).',
	            \'texts\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($oTranslator->getMessages()).'
			};
			oController = (\'undefined\' === typeof '.$sControllerName.')?new Controller(oControllerOptions):new '.$sControllerName.'(oControllerOptions);
		');
	}
}