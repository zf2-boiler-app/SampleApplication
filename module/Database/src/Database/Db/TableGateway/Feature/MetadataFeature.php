<?php
namespace Database\Db\TableGateway\Feature;
class MetadataFeature extends \Zend\Db\TableGateway\Feature\MetadataFeature{
    public function postInitialize(){
    	parent::postInitialize();
    	$this->tableGateway->columnsInfos = array_combine(
    		$this->sharedData['metadata']['columns'],
    		$this->metadata->getColumns($this->tableGateway->table)
    	);
    }
}