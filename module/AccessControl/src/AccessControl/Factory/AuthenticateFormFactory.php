<?php
namespace AccessControl\Factory;
class AuthenticateFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \AccessControl\Form\AuthenticateForm('authenticate');
		return $oForm->setInputFilter(new \AccessControl\InputFilter\AuthenticateInputFilter())->prepare();
    }
}