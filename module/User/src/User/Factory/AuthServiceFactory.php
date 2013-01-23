<?php
namespace User\Factory;
class AuthServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \User\Authentication\AuthenticationService(
			$oServiceLocator->get('AuthStorage'),
			$oServiceLocator->get('AuthAdapter')
		);
    }
}