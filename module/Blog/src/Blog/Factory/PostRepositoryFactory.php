<?php
namespace Blog\Factory;
class PostRepositoryFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		return new \Blog\Repository\PostRepository(
			$oServiceLocator->get('Doctrine\ORM\EntityManager'
		), $class);
    }
}