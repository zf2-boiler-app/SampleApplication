<?php
namespace User\Factory;
class ChangePasswordFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\ChangePasswordForm(null,array(
			'translator' => $oServiceLocator->get('translator'),
			'checkUserLoggedPassword' => array($oServiceLocator->get('UserService'),'checkUserLoggedPassword')
		));
		return $oForm->prepare();
    }
}