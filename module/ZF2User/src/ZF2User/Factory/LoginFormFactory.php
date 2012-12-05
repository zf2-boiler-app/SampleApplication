<?php
namespace ZF2User\Factory;
class LoginFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \ZF2User\Form\Login();
		return $oForm->prepare();
    }
}