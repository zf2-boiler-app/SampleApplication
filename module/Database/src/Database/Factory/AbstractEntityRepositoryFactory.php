<?php
namespace Database\Factory;
class AbstractEntityRepositoryFactory implements \Zend\ServiceManager\AbstractFactoryInterface{

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
			if(!class_exists($sEntityClass = preg_replace('/^(.*)Repository(.*)Repository$/i','$1Entity$2Entity',$sRequestedName)))throw new \InvalidArgumentException(sprintf(
				'Repository "%s" entity class "%s" does not exist',
				$sRequestedName,$sEntityClass
			));
			return $oEntityManager->getRepository($sEntityClass);
		}
		catch(\Exception $oException){
			throw new \BadMethodCallException('Error occured while retrieving Repository for "'.$sRequestedName.'"',$oException->getCode(),$oException);
		}
	}
}