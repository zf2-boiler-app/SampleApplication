<?php
namespace Logger;
class Module{
	/**
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 */
	public function onBootstrap($oEvent){
		$oServiceManager = $oEvent->getApplication()->getServiceManager();

		//Initialize Logger service
		$oServiceManager->get('LoggerService')->start($oEvent->getRequest());
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