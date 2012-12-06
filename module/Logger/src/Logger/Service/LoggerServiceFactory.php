<?php
namespace Logger\Service;
class LoggerServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
        //Configure the logger
        $aConfiguration = $oServiceLocator->get('Config');
        return \Logger\Service\LoggerService::factory(isset($aConfiguration['logger'])?$aConfiguration['logger']:array());
    }
}
