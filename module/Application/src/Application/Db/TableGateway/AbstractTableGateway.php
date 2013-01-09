<?php
namespace Application\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\TableGateway implements \Zend\EventManager\EventsCapableInterface, \Zend\EventManager\SharedEventManagerAwareInterface{
	const EVENT_CREATE_ENTITY = 'CREATE_ENTITY';
	const EVENT_UPDATE_ENTITY = 'UPDATE_ENTITY';
	const EVENT_DELETE_ENTITY = 'DELETE_ENTITY';

	/**
	 * @var string|array
	 */
	protected $primary = null;

	/**
	 * @var \Zend\EventManager\EventManager
	 */
	private $eventManager;

	/**
	 * @var \Zend\EventManager\SharedEventManagerInterface
	 */
	protected $sharedEventManager;

	/**
     * Constructor
     * @param string $table
     * @param Adapter $adapter
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features
     * @param ResultSetInterface $resultSetPrototype
     * @param Sql $sql
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($table, \Zend\Db\Adapter\Adapter $adapter, $features = null, \Zend\Db\ResultSet\ResultSetInterface $resultSetPrototype = null, \Zend\Db\Sql\Sql $sql = null){
    	parent::__construct($table, $adapter,$features,$resultSetPrototype,$sql);
    	$this->attachEvents();
	}

	/**
	 * Attach events to Shared Event Manager, called by constructor
	 * @return \Application\Db\TableGateway\AbstractTableGateway
	 */
	protected function attachEvents(){
		return $this;
	}

	/**
	 * Get EventManager
	 * @return \Zend\EventManager\EventManager
	 */
	public function getEventManager(){
		return $this->eventManager instanceof \Zend\EventManager\EventManager?$this->eventManager:$this->eventManager = new \Zend\EventManager\EventManager(__CLASS__);
	}

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
		return $this->sharedEventManager instanceof \Zend\EventManager\SharedEventManagerInterface
		?$this->sharedEventManager
		:$this->sharedEventManager = \Zend\EventManager\StaticEventManager::getInstance();
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
	 * @see \Zend\Db\TableGateway\AbstractTableGateway::executeInsert()
	 * @param \Zend\Db\Sql\Insert $oInsert
	 * @return int
	 */
	protected function executeInsert(\Zend\Db\Sql\Insert $oInsert){
		$iReturn = parent::executeInsert($oInsert);
		$this->getEventManager()->trigger(self::EVENT_CREATE_ENTITY,$this,array(
			'entity_id' => (int)$this->getLastInsertValue(),
			'entity_table' => $this->table,
			'entity_primary' => $this->primary
		));
		return $iReturn;
	}

	/**
	 * @see \Zend\Db\TableGateway\AbstractTableGateway::executeUpdate()
	 * @param \Zend\Db\Sql\Update $oUpdate
	 * @return int
	 */
	protected function executeUpdate(\Zend\Db\Sql\Update $oUpdate){
		//Retrieve id to be updated
		$aIdToBeUpdated = $this->selectWith($this->sql->select()
			->where($oUpdate->getRawState('where'))
			->columns(is_array($this->primary)?$this->primary:array($this->primary))
		)->toArray();

		//Execute update
		$iReturn = parent::executeUpdate($oUpdate);

		//Trigger update
		$this->getEventManager()->trigger(self::EVENT_UPDATE_ENTITY,$this,array(
			'entity_id' => $aIdToBeUpdated,
			'entity_table' => $this->table,
			'entity_primary' => $this->primary
		));
		return $iReturn;
	}

	/**
	 * @see \Zend\Db\TableGateway\AbstractTableGateway::executeDelete()
	 * @param \Zend\Db\Sql\Delete $oDelete
	 * @return int
	 */
	protected function executeDelete(\Zend\Db\Sql\Delete $oDelete){
		//Retrieve id to be deleted
		$aIdToBeDeleted = $this->selectWith($this->sql->select()
			->where($oDelete->getRawState('where'))
			->columns(is_array($this->primary)?$this->primary:array($this->primary))
		)->toArray();

		//Execute delete
		$iReturn = parent::executeDelete($oDelete);

		//Trigger delete
		$this->getEventManager()->trigger(self::EVENT_DELETE_ENTITY,$this,array(
			'entity_id' => $aIdToBeDeleted,
			'entity_table' => $this->table,
			'entity_primary' => $this->primary
		));
		return $iReturn;
	}
}