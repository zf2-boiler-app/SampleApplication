<?php
namespace AccessControl\Factory;
class LoginFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \AccessControl\Form\LoginForm();
		return $oForm->prepare();
    }
}