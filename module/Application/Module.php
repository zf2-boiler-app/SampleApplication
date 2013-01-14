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
    	if(!$oEvent->getRequest()->isXmlHttpRequest()){
    		//Define layout
    		$aConfiguration = $oEvent->getApplication()->getServiceManager()->get('config');
    		if(!isset($aConfiguration['view_manager']['specialLayout']) || !is_array($aConfiguration['view_manager']['specialLayout']))throw new \Exception('Special Layout config is undefined');
    		$oLayoutView = new \Zend\View\Model\ViewModel();

    		//Set header view
	    	$oHeaderView = new \Zend\View\Model\ViewModel();
	    	if($oEvent->getApplication()->getServiceManager()->get('AuthService')->hasIdentity()){
	    		//Prevent from session value error
				try{
					$oEvent->getViewModel()->loggedUser = $oEvent->getApplication()->getServiceManager()->get('UserService')->getLoggedUser();
					$oHeaderView->setTemplate('header/logged');
				}
				catch(\Exception $oException){
					$oHeaderView->setTemplate('header/unlogged');
				}
	    	}
	    	else $oHeaderView->setTemplate('header/unlogged');
	    	$oEvent->getViewModel()->addChild($oHeaderView,'header');

	    	//Set header view
	    	$oFooterView = new \Zend\View\Model\ViewModel();
	    	$oEvent->getViewModel()->addChild($oFooterView->setTemplate('footer/footer'),'footer');

	    	$oEvent->getViewModel()->addChild(
	    		$oLayoutView->setTemplate(isset($aConfiguration['view_manager']['specialLayout'][$oEvent->getRouteMatch()->getMatchedRouteName()])
	    			?$aConfiguration['view_manager']['specialLayout'][$oEvent->getRouteMatch()->getMatchedRouteName()]
	    			:$aConfiguration['view_manager']['specialLayout']['default']
	    		),
	    		'specialLayout'
	    	);

	    	//Js Controller view helper
	    	$oServiceManager = $oEvent->getApplication()->getServiceManager();
	    	$aConfiguration = $oServiceManager->get('Config');
	    	$oEvent->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('jsController', function() use($oEvent,$aConfiguration,$oServiceManager){
	    		return new \Application\View\Helper\JsControllerHelper($oEvent->getRouteMatch(),$aConfiguration['router']['routes'],$oServiceManager);
	    	});
    	}
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
     public function onError(\Zend\Mvc\MvcEvent $oEvent){
     	if($oEvent->getRequest()->isXmlHttpRequest())$oEvent->getResult()->setTerminal(true);
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
