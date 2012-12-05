<?php
namespace ZF2User\Factory;
class AuthServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \ZF2User\Authentication\AuthenticationService(
			$oServiceLocator->get('AuthStorage'),
			$oServiceLocator->get('AuthAdapter')
		);
    }
}