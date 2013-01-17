<?php
namespace Templating;
class Module{

	/**
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 */
	public function onBootstrap(\Zend\Mvc\MvcEvent $oEvent){
		//Initialize templating service
		$oEvent->getApplication()->getServiceManager()->get('TemplatingService');
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