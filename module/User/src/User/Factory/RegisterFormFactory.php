<?php
namespace User\Factory;
class RegisterFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\RegisterForm(null,array(
			'translator' => $oServiceLocator->get('translator'),
			'checkUserEmailAvailability' => array($oServiceLocator->get('UserModel'),'isUserEmailAvailable')
		));
		return $oForm->prepare();
    }
}