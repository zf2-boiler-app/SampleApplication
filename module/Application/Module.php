<?php
namespace Application;
use Zend\Mvc\ModuleRouteListener;
class Module{

    /**
     * @param \Zend\EventManager\EventInterface $oEvent
     */
    public function onBootstrap(\Zend\Mvc\MvcEvent $oEvent){
    	$oModuleRouteListener = new ModuleRouteListener();
    	$oModuleRouteListener->attach($oEvent->getApplication()->getEventManager());

    	//Add translation for validators
    	\Zend\Validator\AbstractValidator::setDefaultTranslator($oEvent->getApplication()->getServiceManager()->get('translator'),'validator');
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
