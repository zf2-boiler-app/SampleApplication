<?php
namespace User\Factory;
class UserModelFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \User\Model\UserModel($oServiceLocator->get('Zend\Db\Adapter\Adapter'));
    }
}