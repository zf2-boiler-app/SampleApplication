<?php
namespace Application\Translator;
class TranslatorServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
       	/* TODO Remove Error log */error_log(print_r('ok',true));
    	//Configure the translator
        $aConfig = $oServiceLocator->get('Config');
        return \Application\Translator\Translator::factory(isset($aConfig['translator'])?$aConfig['translator']:array());
    }
}
