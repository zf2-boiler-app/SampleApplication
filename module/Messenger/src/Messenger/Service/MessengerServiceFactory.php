<?php
namespace Messenger\Service;
class MessengerServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
        //Configure the messenger
        $aConfiguration = $oServiceLocator->get('Config');
        if(isset($aConfiguration['messenger']['transporters']) 
        && is_array($aConfiguration['messenger']['transporters']))foreach($aConfiguration['messenger']['transporters'] as $sMedia => $oTransporter){
        	if(is_string($oTransporter)){
        		if(class_exists($oTransporter))$aConfiguration['messenger']['transporters'][$sMedia] = new $oTransporter();
        		elseif($oServiceLocator->has($oTransporter))$aConfiguration['messenger']['transporters'][$sMedia] = $oServiceLocator->get($oTransporter);
        	}
        }
        return \Messenger\Service\MessengerService::factory(
        	isset($aConfiguration['messenger'])?$aConfiguration['messenger']:array(),
        	$oServiceLocator->get('AssetsBundleService'),
        	$oServiceLocator->get('InlineStyleService'),
        	$oServiceLocator->get('translator'),
        	$oServiceLocator->get('router')
        );
    }
}