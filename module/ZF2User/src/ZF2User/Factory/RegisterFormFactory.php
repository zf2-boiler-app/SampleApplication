<?php
namespace ZF2User\Factory;
class RegisterFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \ZF2User\Form\RegisterForm(null,array(
			'translator' => $oServiceLocator->get('translator'),
			'checkUserEmailAvailability' => array($oServiceLocator->get('UserService'),'isUserEmailAvailable')
		));
		return $oForm->prepare();
    }
}