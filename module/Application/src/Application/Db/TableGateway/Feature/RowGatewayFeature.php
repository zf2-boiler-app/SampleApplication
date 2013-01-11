<?php
namespace Application\Db\TableGateway\Feature;
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
		if(!($this->tableGateway instanceof \Application\Db\TableGateway\AbstractTableGateway))throw new \Exception(sprintf(
			'This feature "%s" expects the TableGateway to be an instance of \Application\Db\TableGateway\AbstractTableGateway',
			__CLASS__
		));
		if(!$this->tableGateway->resultSetPrototype instanceof \Zend\Db\ResultSet\ResultSet)throw new \Exception(sprintf(
			'This feature "%s" expects the ResultSet to be an instance of \Zend\Db\ResultSet\ResultSet',
			__CLASS__
		));
    	$sRowGatewayClass = $this->constructorArguments[0];
    	$oRowGateway = new $sRowGatewayClass($this->tableGateway);
    	if($oRowGateway instanceof \Application\Db\RowGateway\AbstractRowGateway)$this->tableGateway->resultSetPrototype->setArrayObjectPrototype($oRowGateway);
    	else throw new \Exception($sRowGatewayClass.' is not an instance of \Application\Db\RowGateway\AbstractRowGateway');
	}
}