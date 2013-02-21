<?php
namespace AccessControl\Factory;
class RegisterFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oTranslator = $oServiceLocator->get('translator');
		$oForm = new \AccessControl\Form\RegisterForm('register');
		return $oForm->setTranslator($oTranslator)
		->setInputFilter(new \AccessControl\InputFilter\RegisterInputFilter(
			$oServiceLocator->get('AccessControl\Repository\AuthAccessRepository'),
			$oTranslator
		))->prepare();
    }
}