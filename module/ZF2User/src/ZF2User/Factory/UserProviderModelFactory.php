<?php
namespace ZF2User\Factory;
class UserProviderModelFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \ZF2User\Model\UserProviderModel(
			$oServiceLocator->get('Zend\Db\Adapter\Adapter'),
			$oServiceLocator->get('UserModel')
		);
    }
}