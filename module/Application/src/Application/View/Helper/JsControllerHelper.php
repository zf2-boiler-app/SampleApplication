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
	 * @return \Application\View\Helper\JsController
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
	 * Retrieve translation messages
	 * @return array:
	 */
	protected function getTranslationMessages(){
		$oTranslator = $this->getServiceLocator()->getServiceLocator()->get('translator');
		return $aMessages = array_merge(
			$oTranslator->getMessages(),
			$oTranslator->getMessages(null,'validator')
		);
	}

	/**
	 * Invoke helper
	 * @return
	 */
	public function __invoke(){
		if($this->routeMatch){
			$sControllerName = str_ireplace('\\','',$this->routeMatch->getParam('controller'));
			$sControllerActionName = $sControllerName.ucfirst($this->routeMatch->getParam('action'));
		}
		else $sControllerName = $sControllerActionName = 'NoController';

		return $this->getServiceLocator()->get('inlineScript')->__invoke(\Zend\View\Helper\HeadScript::SCRIPT)->appendScript('
			var oControllerOptions = {
				\'locale\':'.$this->getServiceLocator()->get('escapeJson')->__invoke(str_ireplace('_','-',$this->getServiceLocator()->getServiceLocator()->get('translator')->getLocale())).',
	            \'texts\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($this->getTranslationMessages()).',
				\'routes\':'.$this->getServiceLocator()->get('escapeJson')->__invoke($this->routes).',
			};
			var oController;
			if(\'undefined\' !== typeof '.$sControllerActionName.')oController = new '.$sControllerActionName.'(oControllerOptions);
			else if(\'undefined\' !== typeof '.$sControllerName.')oController = new '.$sControllerName.'(oControllerOptions);
			else oController = new Controller(oControllerOptions);
		');
	}
}