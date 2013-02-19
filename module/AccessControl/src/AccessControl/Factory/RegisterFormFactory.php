<?php
namespace AccessControl\Factory;
class RegisterFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \AccessControl\Form\RegisterForm('register');
		return $oForm
		->setTranslator($oServiceLocator->get('translator'))
		->setInputFilter(new \AccessControl\InputFilter\RegisterInputFilter())
		->prepare();
    }
}