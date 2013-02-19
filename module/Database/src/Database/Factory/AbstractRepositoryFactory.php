<?php
namespace Database\Factory;
class AbstractRepositoryFactory implements \Zend\ServiceManager\AbstractFactoryInterface{

	/**
	 * Determine if we can create a service with name
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @param string $sName
	 * @param string $sRequestedName
	 * @return boolean
	 */
	public function canCreateServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator, $sName, $sRequestedName){
		return preg_match('/^(.*)Repository(.*)Repository$/i',$sRequestedName);
	}

	/**
	 * Create service with name
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @param string $sName
	 * @param string $sRequestedName
	 * @return object
	 */
	public function createServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator, $sName, $sRequestedName){
		/* @var $oEntityManager \Doctrine\ORM\EntityManager */
		$oEntityManager = $oServiceLocator->get('Doctrine\ORM\EntityManager');
		try{
			return $oEntityManager->getRepository(preg_replace('/^(.*)Repository(.*)Repository$/i','$1Entity$2Entity',$sRequestedName));
		}
		catch(\Exception $oException){
			error_log(print_r($oException->__toString(),true));
		}
	}
}