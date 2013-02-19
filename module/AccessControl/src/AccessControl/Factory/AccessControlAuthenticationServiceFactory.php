<?php
namespace AccessControl\Factory;
class AccessControlAuthenticationServiceFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$aConfiguration = $oServiceLocator->get('config');
		return \AccessControl\Authentication\AccessControlAuthenticationService::factory(
			isset($aConfiguration['authentication'])?$aConfiguration['authentication']:array(),
			$oServiceLocator
		);
    }
}