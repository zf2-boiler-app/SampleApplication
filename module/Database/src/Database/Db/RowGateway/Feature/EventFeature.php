<?php
namespace Database\Db\RowGateway\Feature;
class EventFeature extends \Zend\Db\RowGateway\Feature\AbstractFeature implements \Zend\EventManager\EventsCapableInterface{

	/**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $eventManager = null;

    /**
     * @var null
     */
    protected $event = null;

    /**
     * @param EventManagerInterface $oEventManager
     * @param EventFeature\TableGatewayEvent $oRowGatewayEvent
     */
    public function __construct(\Zend\EventManager\EventManagerInterface $oEventManager = null, \Database\Db\RowGateway\Feature\EventFeature\RowGatewayEvent $oRowGatewayEvent = null){
        $this->eventManager = ($oEventManager instanceof \Zend\EventManager\EventManagerInterface)?$oEventManager:new \Zend\EventManager\EventManager();
        $this->eventManager->setIdentifiers(array('Database\Db\RowGateway\RowGateway'));
        $this->event = $oRowGatewayEvent?:new \Database\Db\RowGateway\Feature\EventFeature\RowGatewayEvent();
    }

    /**
     * Retrieve composed event manager instance
     * @return \Zend\EventManager\EventManagerInterface
     */
    public function getEventManager(){
        return $this->eventManager;
    }

    /**
     * Retrieve composed event instance
     * @return \Database\Db\RowGateway\Feature\EventFeature\RowGatewayEvent
     */
    public function getEvent(){
        return $this->event;
    }

    /**
     * @param \Zend\Db\Sql\Update $oUpdate
     */
	public function preSave(\Zend\Db\Sql\Update $oUpdate){
		$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array('update' => $oUpdate)));
	}

	/**
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
	 * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
	 * @param array $aSavedId
	 */
	public function postSave(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult, array $aSavedId = array()){
		$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array(
			'statement' => $oStatement,
            'result' => $oResult,
    		'table' => $this->rowGateway->table,
        	'primaryKey' => $this->rowGateway->primaryKeyColumn,
    		'updatedIds' => $aSavedId
		)));
	}

	/**
	 * @param \Zend\Db\Sql\Delete $oDelete
	 */
	public function preDelete(\Zend\Db\Sql\Delete $oDelete){
		$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array('delete' => $oDelete)));
	}

	/**
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
	 * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
	 * @param array $aDeletedId
	 */
	public function postDelete(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult, array $aDeletedId = array()){
		$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array(
			'statement' => $oStatement,
			'result' => $oResult,
			'table' => $this->rowGateway->table,
			'primaryKey' => $this->rowGateway->primaryKeyColumn,
			'deletedIds' => $aDeletedId
		)));
	}
}