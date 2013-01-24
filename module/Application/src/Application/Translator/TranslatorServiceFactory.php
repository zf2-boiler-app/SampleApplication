<?php
namespace Application\Translator;
class TranslatorServiceFactory implements \Zend\ServiceManager\FactoryInterface{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
    	//Configure the translator
        $aConfig = $oServiceLocator->get('Config');
        return \Application\Translator\Translator::factory(isset($aConfig['translator'])?$aConfig['translator']:array());
    }
}
