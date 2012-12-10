<?php
namespace Application\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\TableGateway implements \Zend\EventManager\SharedEventManagerAwareInterface{
	const EVENT_CREATE_ENTITY = 'create_entity';
	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;

	/**
	 * Inject a SharedEventManager instance
	 * @param \Zend\EventManager\SharedEventManagerInterface $oSharedEventManager
	 * @return \Logger\Service\LoggerService
	 */
	public function setSharedManager(\Zend\EventManager\SharedEventManagerInterface $oSharedEventManager){
		$this->sharedEventManager = $oSharedEventManager;
		return $this;
	}

	/**
	 * Get shared collections container
	 * @return \Zend\EventManager\SharedEventManagerInterface
	 */
	public function getSharedManager(){
		if(!($this->sharedEventManager instanceof \Zend\EventManager\SharedEventManagerInterface))$this->sharedEventManager = \Zend\EventManager\StaticEventManager::getInstance();
		return $this->sharedEventManager;
	}

	/**
	 * Remove any shared collections
	 * @return \Logger\Service\LoggerService
	 */
	public function unsetSharedManager(){
		$this->sharedEventManager = null;
		return $this;
	}

	/**
	 * Insert
	 * @param  array $set
	 * @return int
	 */
	public function insert($aSet){
		$iEntityId = parent::insert($aSet);
		$this->getSharedManager()->trigger(self::EVENT_CREATE_ENTITY,$this,array(
			'entity_id' => $iEntityId,
			'entity_table' => $this->table
		));
		return $iEntityId;
	}
}