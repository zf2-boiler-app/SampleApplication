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

    	if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer)$oEventManager->attach(
    		\Zend\Mvc\MvcEvent::EVENT_RENDER,
    		array($this, 'onRender')
    	);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onRender(\Zend\Mvc\MvcEvent $oEvent){
    	//Set header view
    	$oHeaderView = new \Zend\View\Model\ViewModel();
    	if($oEvent->getApplication()->getServiceManager()->get('AuthService')->hasIdentity()){
	    	$oHeaderView->setTemplate('header/logged');
	    	$oEvent->getViewModel()->loggedUser = $oEvent->getApplication()->getServiceManager()->get('UserService')->getLoggedUser();
    	}
    	else $oHeaderView->setTemplate('header/unlogged');
    	$oEvent->getViewModel()->addChild($oHeaderView,'header');
    	//Js Controller view helper
    	$oServiceManager = $oEvent->getApplication()->getServiceManager();
    	$aConfiguration = $oServiceManager->get('Config');
    	$oEvent->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('jsController', function() use($oEvent,$aConfiguration,$oServiceManager){
    		return new \Application\View\Helper\JsController($oEvent->getRouteMatch(),$aConfiguration['router']['routes'],$oServiceManager);
    	});
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
