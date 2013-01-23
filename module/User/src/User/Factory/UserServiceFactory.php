<?php
namespace User\Factory;
class UserServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oUserService = new \User\Service\UserService();
		return $oUserService->setServiceLocator($oServiceLocator);
    }
}