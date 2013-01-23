<?php
namespace User\Factory;
class UserProviderModelFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \User\Model\UserProviderModel(
			$oServiceLocator->get('Zend\Db\Adapter\Adapter'),
			$oServiceLocator->get('UserModel')
		);
    }
}