<?php
namespace User\Factory;
class ChangeAvatarFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \User\Form\ChangeAvatarForm();
		return $oForm->prepare();
    }
}