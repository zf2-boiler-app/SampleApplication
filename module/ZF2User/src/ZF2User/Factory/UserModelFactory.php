<?php
namespace ZF2User\Factory;
class UserModelFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \ZF2User\Model\UserModel($oServiceLocator->get('Zend\Db\Adapter\Adapter'));
    }
}