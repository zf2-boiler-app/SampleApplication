<?php
namespace Application\Db\TableGateway\Feature;
class EventFeature extends \Zend\Db\TableGateway\Feature\EventFeature{

	/**
	 * @var array
	 */
	protected $primaryKey;

	/**
	 * Constructor
	 * @param \Zend\EventManager\EventManagerInterface $oEventManager
	 * @param \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent $oTableGatewayEvent
	 */
	public function __construct(\Zend\EventManager\EventManagerInterface $oEventManager = null, \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent $oTableGatewayEvent = null){
		parent::__construct($oEventManager,$oTableGatewayEvent?:new \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent());
		$this->eventManager->setIdentifiers('Application\Db\TableGateway\TableGateway');
	}

	/**
	 * @see \Zend\Db\TableGateway\Feature\EventFeature::postInsert()
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
     * @param array $aInsertedId
	 */
    public function postInsert(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult, array $aInsertedId = array()){
        $this->eventManager->trigger($this->event->setName(__FUNCTION__)
    	->setParams(array(
            'statement' => $oStatement,
            'result' => $oResult,
    		'table' => $this->tableGateway->getTable(),
        	'primaryKey' => $this->getPrimaryKey(),
    		'insertedId' => $aInsertedId
        )));
        unset($this->insert);
    }

    /**
	 * @see \Zend\Db\TableGateway\Feature\EventFeature::postUpdate()
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
     * @param array $aUpdatedIds
	 */
    public function postUpdate(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult, array $aUpdatedIds = array()){
        $this->eventManager->trigger($this->event->setName(__FUNCTION__)
        ->setParams(array(
        	'statement' => $oStatement,
        	'result' => $oResult,
    		'table' => $this->tableGateway->getTable(),
        	'primaryKey' => $this->getPrimaryKey(),
        	'updatedIds' => $aUpdatedIds
        )));
    }

    /**
     * @see \Zend\Db\TableGateway\Feature\EventFeature::postDelete()
     * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
     * @param array $aDeletedIds
     */
    public function postDelete(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult, array $aDeletedIds = array()){
    	$this->eventManager->trigger($this->event->setName(__FUNCTION__)
    	->setParams(array(
    		'statement' => $oStatement,
    		'result' => $oResult,
    		'table' => $this->tableGateway->getTable(),
    		'primaryKey' => $this->getPrimaryKey(),
        	'deletedIds' => $aDeletedIds
    	)));
    }

    /**
     * @throws \Exception
     * @return array
     */
    protected function getPrimaryKey(){
    	if(is_array($this->primaryKey))return $this->primaryKey;
    	//Get primary key from metadata feature
    	$oMetadataFeature = $this->tableGateway->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
    	if($oMetadataFeature === false || !isset($oMetadataFeature->sharedData['metadata']))throw new \Exception('No MetadataFeature could be consulted');
    	return $this->primaryKey = (array)$oMetadataFeature->sharedData['metadata']['primaryKey'];
    }
}