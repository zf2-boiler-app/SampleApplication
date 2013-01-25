<?php
namespace User\Factory;
class UserAccountServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oUserService = new \User\Service\UserAccountService();
		return $oUserService->setServiceLocator($oServiceLocator);
    }
}