<?php
namespace Application\View\Helper;
class JsControllerHelper extends \Zend\View\Helper\AbstractHelper implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

	/**
	 * @var \Zend\Mvc\Router\Http\RouteMatch
	 */
	private $routeMatch;
	
	/**
	 * @var array
	 */
	private $routes;

	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * Constructor
	 * @param string $oRouteMatch
	 * @param array $aRoutes
	 */
	public function __construct($oRouteMatch = null,array $aRoutesConfig = array(),\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		if($oRouteMatch instanceof \Zend\Mvc\Router\Http\RouteMatch)$this->routeMatch = $oRouteMatch;
		$this->setServiceLocator($oServiceLocator)->setRoutes($aRoutesConfig);
	}
	
	/**
	 * @param array $aRoutesConfig
	 * @param string $sRoutePrefix
	 * @return \Application\View\Helper\JsController
	 */
	public function setRoutes(array $aRoutesConfig,$sRouteParent = null){
		$oRouter = $this->getServiceLocator()->get('router');
		foreach($aRoutesConfig as $sRouteName => $aInfosRoute){
			if($aInfosRoute['type'] !== 'Zend\Mvc\Router\Http\Literal')continue;
			$this->routes[$sRouteName = empty($sRouteParent)?$sRouteName:$sRouteParent.'/'.$sRouteName] = $oRouter->assemble(array(), array('name' => $sRouteName));
			if(isset($aInfosRoute['child_routes']))$this->setRoutes($aInfosRoute['child_routes'],$sRouteName);
		}
		return $this;
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

	/**
	 * Invoke helper
	 * @return 
	 */
	public function __invoke(){
		/* @var $oTranslator \Application\Translator\Translator */
		$oTranslator = $this->getServiceLocator()->getServiceLocator()->get('translator');
		$sControllerName = $this->routeMatch?str_ireplace('\\','',$this->routeMatch->getParam('controller')):'NoController';
		return $this->getServiceLocator()->get('inlineScript')->__invoke(\Zend\View\Helper\HeadScript::SCRIPT)->appendScript('
			var oControllerOptions = {
				\'locale\':'.$this->getServiceLocator()->get('escapeJson')->__invoke(str_ireplace('_','-',$oTranslator->getLocale())).',
	            \'texts\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($oTranslator->getMessages()).',
				\'routes\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($this->routes).',
			};
			oController = (\'undefined\' === typeof '.$sControllerName.')?new Controller(oControllerOptions):new '.$sControllerName.'(oControllerOptions);
		');
	}
}