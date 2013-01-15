<?php
namespace Templating\Factory;
class TemplatingServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
        //Configure the logger
        $aConfiguration = $oServiceLocator->get('Config');
       	return \Templating\Service\TemplatingService::factory(isset($aConfiguration['templating'])?$aConfiguration['templating']:array());
    }
}