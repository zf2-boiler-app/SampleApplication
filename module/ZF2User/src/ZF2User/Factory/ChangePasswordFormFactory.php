<?php
namespace ZF2User\Factory;
class ChangePasswordFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \ZF2User\Form\ChangePasswordForm();
		return $oForm->prepare();
    }
}