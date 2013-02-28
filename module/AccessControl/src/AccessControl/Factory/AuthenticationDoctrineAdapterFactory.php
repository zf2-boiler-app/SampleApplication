<?php
namespace AccessControl\Factory;
class AuthenticationDoctrineAdapterFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \AccessControl\Authentication\Adapter\AuthenticationDoctrineAdapter($oServiceLocator->get('AccessControlService'));
    }
}