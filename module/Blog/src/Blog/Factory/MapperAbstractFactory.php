<?php
namespace Blog\Factory;
class MapperAbstractFactory implements \Zend\ServiceManager\AbstractFactoryInterface{
    /**
     * Determine if we can create a service with name
     * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
     * @param string $sName
     * @param string $sRequestedName
     * @return boolean
     */
    public function canCreateServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator, $sName, $sRequestedName){
    	return preg_match('/MapperInterface$/i',$sRequestedName);
    }

    /**
     * Create service with name
     * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
     * @param string $sName
     * @param string $sRequestedName
     * @return object
     */
    public function createServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator, $sName, $sRequestedName){
        $aRequestParts = explode('\\', $sRequestedName);

        /* @var $oEntityManager \Doctrine\ORM\EntityManager */
        $oEntityManager = $oServiceLocator->get('Doctrine\ORM\EntityManager');
        try{
        	return $oEntityManager->getRepository('Blog\\Entity\\'.preg_replace('/MapperInterface$/i','Entity',end($aRequestParts)));
        }
        catch(\Exception $oException){
        	/* TODO Remove Error log */error_log(print_r($oException->__toString(),true));
        }
    }
}