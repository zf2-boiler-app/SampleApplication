<?php
namespace ZF2User\Factory;
class AuthStorageFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \Zend\Authentication\Storage\Session(null,null,$oServiceLocator->get('SessionManager'));
    }
}