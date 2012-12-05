<?php
namespace Application\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\TableGateway implements \Zend\EventManager\EventManagerAwareInterface{
	/**
	 * @var \Zend\EventManager\EventManagerInterface
	 */
	protected $events;
	
	/**
	 * @param \Zend\EventManager\EventManagerInterface $oEvents
	 * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
	 * @return \Application\Db\TableGateway\AbstractTableGateway
	 */
	public function setEventManager(\Zend\EventManager\EventManagerInterface $oEvents){
		$oEvents->setIdentifiers(array(__CLASS__,get_called_class()));
		$this->events = $oEvents;
		return $this;
	}
	
	/**
	 * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
	 * @return \Zend\EventManager\EventManagerInterface
	 */
	public function getEventManager(){
		if(null === $this->events)$this->setEventManager(new \Zend\EventManager\EventManager());
		return $this->events;
	}
}