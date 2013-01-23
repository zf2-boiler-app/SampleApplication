<?php
namespace User\Factory;
class SessionManagerFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \Zend\Session\SessionManager();
    }
}