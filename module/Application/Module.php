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

    	$oEventManager->attach(
    		'*',
    		function($oEvent){
    			/* TODO Remove Error log */error_log(print_r($oEvent->getName(),true));
    		}
    	);

    	//Process for render MVC event
    	if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer)$oEventManager->attach(
    		\Zend\Mvc\MvcEvent::EVENT_RENDER,
    		array($this, 'onRender')
    	);

    	//Process for error MVC event
    	if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer)$oEventManager->attach(
    		array(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR),
    		array($this, 'onError')
    	);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onRender(\Zend\Mvc\MvcEvent $oEvent){
    	$oRequest = $oEvent->getRequest();

    	if(!($oRequest instanceof \Zend\Http\Request) || $oRequest->isXmlHttpRequest()){
    		if(($oView = $oEvent->getResult()) instanceof \Zend\View\Model\ModelInterface)$oEvent->setResult($oView->setTerminal(true));
    	}
    	elseif(
    		($oView = $oEvent->getResult()) instanceof \Zend\View\Model\ModelInterface
    		&& !$oView->terminate()
    	){
	    	//Js Controller view helper
	    	$oServiceManager = $oEvent->getApplication()->getServiceManager();
	    	$aConfiguration = $oServiceManager->get('Config');
	    	$oEvent->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('jsController', function() use($oEvent,$aConfiguration,$oServiceManager){
	    		return new \Application\View\Helper\JsControllerHelper($oEvent->getRouteMatch(),$aConfiguration['router']['routes'],$oServiceManager);
	    	});

	    	//Set matchedRouteName var to layout
	    	$oRouteMatch= $oEvent->getRouteMatch();
	    	if($oRouteMatch instanceof \Zend\Mvc\Router\RouteMatch)$oEvent->getViewModel()->setVariable('matchedRouteName',$oRouteMatch->getMatchedRouteName());
    	}
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
     public function onError(\Zend\Mvc\MvcEvent $oEvent){
     	$oRequest = $oEvent->getRequest();
     	if($oEvent->getName() === 'render.error'){
     		if(!($oException = $oEvent->getParam('exception')) instanceof \Exception)$oException = new \Exception($oEvent->getError());
     		error_log(print_r($oException->__toString(),true));
     	}
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
    		'Zend\Loader\ClassMapAutoloader' => array(
    			__DIR__ . '/autoload_classmap.php',
    		)
    	);
    }
}
