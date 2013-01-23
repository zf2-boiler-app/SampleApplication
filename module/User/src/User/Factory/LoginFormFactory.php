<?php
namespace User\Factory;
class LoginFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\LoginForm();
		return $oForm->prepare();
    }
}