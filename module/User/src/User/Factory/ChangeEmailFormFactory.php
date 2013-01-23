<?php
namespace User\Factory;
class ChangeEmailFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\ChangeEmailForm(null,array(
			'translator' => $oServiceLocator->get('translator'),
			'userEmail' => $oServiceLocator->get('UserService')->getLoggedUser()->getUserEmail(),
			'checkUserEmailAvailability' => array($oServiceLocator->get('UserModel'),'isUserEmailAvailable')
		));
		return $oForm->prepare();
    }
}