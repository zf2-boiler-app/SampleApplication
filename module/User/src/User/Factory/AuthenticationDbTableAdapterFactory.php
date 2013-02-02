<?php
namespace User\Factory;
class AuthenticationDbTableAdapterFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \User\Authentication\Adapter\AuthenticationDbTableAdapter(
			$oServiceLocator->get('Zend\Db\Adapter\Adapter'),
			$oServiceLocator->get('UserModel')->getTable(),
			'user_email',
			'user_password',
			'MD5(?)'
		);
    }
}