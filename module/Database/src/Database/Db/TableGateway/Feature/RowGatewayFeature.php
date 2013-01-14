<?php
namespace Database\Db\TableGateway\Feature;
class RowGatewayFeature extends \Zend\Db\TableGateway\Feature\RowGatewayFeature{
	/**
	 * @param null $primaryKey
	 */
	public function __construct($sRowGatewayClass){
		if(!is_string($sRowGatewayClass)) throw new \Exception('RowGateway Class expects string, '.gettype($sRowGatewayClass).' given');
		if(!class_exists($sRowGatewayClass))throw new \Exception('RowGateway Class "'.$sRowGatewayClass.'" doesn\'t exist');
		$this->constructorArguments = func_get_args();
	}

	public function postInitialize(){
		if(!($this->tableGateway instanceof \Database\Db\TableGateway\AbstractTableGateway))throw new \Exception(sprintf(
			'This feature "%s" expects the TableGateway to be an instance of \Database\Db\TableGateway\AbstractTableGateway',
			__CLASS__
		));

		$oMetadata = $this->tableGateway->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
		if($oMetadata === false || !isset($oMetadata->sharedData['metadata'])) {
			throw new Exception\RuntimeException(
				'No information was provided to the RowGatewayFeature and/or no MetadataFeature could be consulted to find the primary key necessary for RowGateway object creation.'
			);
		}
		$aPrimaryKey = (array)$oMetadata->sharedData['metadata']['primaryKey'];

		if(!$this->tableGateway->resultSetPrototype instanceof \Zend\Db\ResultSet\ResultSet)throw new \Exception(sprintf(
			'This feature "%s" expects the ResultSet to be an instance of \Zend\Db\ResultSet\ResultSet',
			__CLASS__
		));
    	$sRowGatewayClass = $this->constructorArguments[0];
    	$oRowGateway = new $sRowGatewayClass($aPrimaryKey,$this->tableGateway);
    	if($oRowGateway instanceof \Database\Db\RowGateway\AbstractRowGateway)$this->tableGateway->resultSetPrototype->setArrayObjectPrototype($oRowGateway);
    	else throw new \Exception($sRowGatewayClass.' is not an instance of \Database\Db\RowGateway\AbstractRowGateway');
	}
}