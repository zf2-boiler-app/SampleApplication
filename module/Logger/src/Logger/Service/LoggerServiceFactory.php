<?php
namespace Logger\Service;
class LoggerServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
        //Configure the logger
        $aConfiguration = $oServiceLocator->get('Config');
        if(
        	isset($aConfiguration['logger']['adapters'])
        	&& is_string($aConfiguration['logger']['adapters'])
        	&& !class_exists($aConfiguration['logger']['adapters'])
        )$aConfiguration['logger']['adapters'] = $oServiceLocator->get($aConfiguration['logger']['adapters']);
        return \Logger\Service\LoggerService::factory(isset($aConfiguration['logger'])?$aConfiguration['logger']:array());
    }
}
