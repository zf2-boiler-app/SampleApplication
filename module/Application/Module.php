<?php
namespace Application;
class Module{

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onBootstrap(\Zend\Mvc\MvcEvent $oEvent){
    	$oModuleRouteListener = new \Zend\Mvc\ModuleRouteListener();
    	$oModuleRouteListener->attach($oEventManager = $oEvent->getApplication()->getEventManager());

    	/* @var $oServiceManager \Zend\ServiceManager\ServiceManager */
    	$oServiceManager = $oEvent->getApplication()->getServiceManager();

    	//Add translation for validators
    	\Zend\Validator\AbstractValidator::setDefaultTranslator($oServiceManager->get('translator'),'validator');

    	//Process for render MVC event
    	if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer)$oEventManager->attach(
    		\Zend\Mvc\MvcEvent::EVENT_RENDER,
    		array($this, 'onRender')
    	);

    	//Process for error MVC event
    	if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer)$oEventManager->attach(
    		\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,
    		array($this, 'onError')
    	);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onRender(\Zend\Mvc\MvcEvent $oEvent){
    	$oRequest = $oEvent->getRequest();
    	if($oRequest instanceof \Zend\Http\Request && !$oRequest->isXmlHttpRequest()){
	    	//Js Controller view helper
	    	$oServiceManager = $oEvent->getApplication()->getServiceManager();
	    	$aConfiguration = $oServiceManager->get('Config');
	    	$oEvent->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('jsController', function() use($oEvent,$aConfiguration,$oServiceManager){
	    		return new \Application\View\Helper\JsControllerHelper($oEvent->getRouteMatch(),$aConfiguration['router']['routes'],$oServiceManager);
	    	});

	    	//Set matchedRouteName var to layout
	    	$oEvent->getViewModel()->setVariable('matchedRouteName', $oEvent->getRouteMatch()->getMatchedRouteName());
    	}
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
     public function onError(\Zend\Mvc\MvcEvent $oEvent){
     	$oRequest = $oEvent->getRequest();
     	if(!($oRequest instanceof \Zend\Http\Request) || $oRequest->isXmlHttpRequest())$oEvent->getResult()->setTerminal(true);
    }

    /**
     * @return array
     */
    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     * @return array
     */
    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__)
            )
        );
    }
}
