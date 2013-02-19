<?php
namespace AccessControl\Factory;
class AuthenticationStorageFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \Zend\Authentication\Storage\Session(null,null,$oServiceLocator->get('SessionManager'));
    }
}