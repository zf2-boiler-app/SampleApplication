<?php
namespace Application\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\TableGateway implements \Zend\EventManager\EventsCapableInterface{
	const EVENT_CREATE_ENTITY = 'CREATE_ENTITY';
	
	/**
	 * @var string|array
	 */
	protected $primary = null;
	
	/**
	 * @var \Zend\EventManager\EventManager
	 */
	private $eventManager;

	/**
	 * Get EventManager
	 * @return \Zend\EventManager\EventManager
	 */
	public function getEventManager(){
		return $this->eventManager instanceof \Zend\EventManager\EventManager?$this->eventManager:$this->eventManager = new \Zend\EventManager\EventManager(__CLASS__);
	}

	/**
	 * Insert
	 * @param  array $aSet
	 * @return int
	 */
	public function insert($aSet){
		error_log(print_r($aSet,true));
		$iReturn = parent::insert($aSet);
		$this->getEventManager()->trigger(self::EVENT_CREATE_ENTITY,$this,array(
			'entity_id' => (int)$this->getLastInsertValue(),
			'entity_table' => $this->table,
			'entity_primary' => $this->primary
		));
		return $iReturn;
	}
}