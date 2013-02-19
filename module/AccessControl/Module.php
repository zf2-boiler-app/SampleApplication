<?php
namespace AccessControl;
class Module{

	/**
	 * @param \Zend\Mvc\MvcEvent $oEvent
	 */
	public function onBootstrap(\Zend\Mvc\MvcEvent $oEvent){
		/* @var $oServiceManager \Zend\ServiceManager\ServiceManager */
		$oServiceManager = $oEvent->getApplication()->getServiceManager();

		//Set logged user to layout if exists
		if($oServiceManager->get('ViewRenderer') instanceof \Zend\View\Renderer\PhpRenderer){
			try{
				if($oServiceManager->get('AccessControlAuthenticationService')->hasIdentity())$oEvent->getViewModel()->loggedUser = $oServiceManager->get('AccessControlService')->getLoggedUser();
			}
			catch(\Exception $oException){}
		}
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