<?php
namespace ZF2User\Factory;
class ResetPasswordFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \ZF2User\Form\ResetPasswordForm();
		return $oForm->prepare();
    }
}