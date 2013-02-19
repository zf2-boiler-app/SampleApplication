<?php
namespace User\Factory;
class ResetPasswordFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\ResetPasswordForm();
		return $oForm->prepare();
    }
}