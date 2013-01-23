<?php
namespace User\Factory;
class AuthAdapterFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \Zend\Authentication\Adapter\DbTable(
			$oServiceLocator->get('Zend\Db\Adapter\Adapter'),
			$oServiceLocator->get('UserModel')->getTable(),
			'user_email',
			'user_password',
			'MD5(?)'
		);
    }
}