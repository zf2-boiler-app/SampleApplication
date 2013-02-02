<?php
namespace User\Factory;
class UserAuthenticationServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$aConfiguration = $oServiceLocator->get('config');
		return \User\Authentication\UserAuthenticationService::factory(
			isset($aConfiguration['authentication'])?$aConfiguration['authentication']:array(),
			$oServiceLocator
		);
    }
}