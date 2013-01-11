<?php
namespace Application\Db\TableGateway\Feature;
class EventFeature extends \Zend\Db\TableGateway\Feature\EventFeature{

	/**
	 * @var array
	 */
	protected $primaryKey;

	/**
	 * @var \Zend\Db\Sql\Insert
	 */
	protected $insert;

	/**
	 * @var \Zend\Db\Sql\Update
	 */
	protected $update;

	/**
	 * @var \Zend\Db\Sql\Delete
	 */
	protected $delete;

	/**
	 * @see \Zend\Db\TableGateway\Feature\EventFeature::preInsert()
	 * @param \Zend\Db\Sql\Insert $oInsert
	 */
	public function preInsert(\Zend\Db\Sql\Insert $oInsert){
		if($this->insert)throw new \Exception('Insert is already defined');
		$this->insert = $oInsert;
		$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array('insert' => $oInsert)));
	}

	/**
	 * @see \Zend\Db\TableGateway\Feature\EventFeature::postInsert()
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
	 */
    public function postInsert(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult){
    	if(!($this->insert instanceof \Zend\Db\Sql\Insert))throw new \Exception('Insert is undefined');
        $this->eventManager->trigger($this->event->setName(__FUNCTION__)
    	->setParams(array(
    		'insert' => $this->insert,
            'statement' => $oStatement,
            'result' => $oResult,
        	'primaryKey' => $this->getPrimaryKey()
        )));
        unset($this->insert);
    }

    /**
     * @see \Zend\Db\TableGateway\Feature\EventFeature::preUpdate()
     * @param \Zend\Db\Sql\Update $oUpdate
     */
    public function preUpdate(\Zend\Db\Sql\Update $oUpdate){
    	if($this->update)throw new \Exception('Update is already defined');
    	$this->update = $oUpdate;
        $this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array('update' => $oUpdate)));
    }

    /**
	 * @see \Zend\Db\TableGateway\Feature\EventFeature::postUpdate()
	 * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
	 */
    public function postUpdate(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult){
        if(!($this->update instanceof \Zend\Db\Sql\Update))throw new \Exception('Update is undefined');
        $this->eventManager->trigger($this->event->setName(__FUNCTION__)
        ->setParams(array(
        	'update' => $this->update,
        	'statement' => $oStatement,
        	'result' => $oResult,
        	'primaryKey' => $this->getPrimaryKey()
        )));
        unset($this->update);
    }

    /**
     * @see \Zend\Db\TableGateway\Feature\EventFeature::preDelete()
     * @param \Zend\Db\Sql\Delete $oDelete
     */
    public function preDelete(\Zend\Db\Sql\Delete $oDelete){
    	if($this->delete)throw new \Exception('Delete is already defined');
    	$this->delete = $oDelete;
    	$this->eventManager->trigger($this->event->setName(__FUNCTION__)->setParams(array('delete' => $oDelete)));
    }

    /**
     * @see \Zend\Db\TableGateway\Feature\EventFeature::postDelete()
     * @param \Zend\Db\Adapter\Driver\StatementInterface $oStatement
     * @param \Zend\Db\Adapter\Driver\ResultInterface $oResult
     */
    public function postDelete(\Zend\Db\Adapter\Driver\StatementInterface $oStatement, \Zend\Db\Adapter\Driver\ResultInterface $oResult){
    	if(!($this->delete instanceof \Zend\Db\Sql\Delete))throw new \Exception('Delete is undefined');
    	$this->eventManager->trigger($this->event->setName(__FUNCTION__)
    	->setParams(array(
    		'delete' => $this->delete,
    		'statement' => $oStatement,
    		'result' => $oResult,
    		'primaryKey' => $this->getPrimaryKey()
    	)));
    	unset($this->delete);
    }

    /**
     * @throws \Exception
     * @return array
     */
    protected function getPrimaryKey(){
    	if($this->primaryKey)return $this->primaryKey;
    	//Get primary key from metadata feature
    	$oMetadataFeature = $this->tableGateway->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
    	if($oMetadataFeature === false || !isset($oMetadataFeature->sharedData['metadata']))throw new \Exception('No MetadataFeature could be consulted');
    	$this->primaryKey = (array)$oMetadataFeature->sharedData['metadata']['primaryKey'];
    }
}
