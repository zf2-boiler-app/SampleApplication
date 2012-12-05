<?php
namespace ZF2User\Factory;
class UserServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oUserService = new \ZF2User\Service\UserService();
		return $oUserService->setServiceLocator($oServiceLocator);
    }
}