<?php
namespace Database\Db\TableGateway;
class TableGateway extends \Database\Db\TableGateway\AbstractTableGateway{
	/**
	 * Constructor
	 * @param string $sTable
	 * @param \Zend\Db\Adapter\Adapter $oAdapter
	 * @param string $sRowGatewayClass
	 */
	public function __construct($sTable, \Zend\Db\Adapter\Adapter $oAdapter, $sRowGatewayClass){
		// table
		if(!(is_string($sTable) || $sTable instanceof TableIdentifier))throw new Exception\InvalidArgumentException('Table name must be a string or an instance of Zend\Db\Sql\TableIdentifier');
		$this->table = $sTable;

		// adapter
		$this->adapter = $oAdapter;

		// process features
		$this->featureSet = new \Zend\Db\TableGateway\Feature\FeatureSet(array(
			new \Database\Db\TableGateway\Feature\MetadataFeature(),
			new \Database\Db\TableGateway\Feature\EventFeature(),
			new \Database\Db\TableGateway\Feature\RowGatewayFeature($sRowGatewayClass)
		));

		// Sql object (factory for select, insert, update, delete)
		$this->sql = new \Zend\Db\Sql\Sql($this->adapter, $this->table);

		// check sql object bound to same table
		if($this->sql->getTable() != $this->table)throw new Exception\InvalidArgumentException('The table inside the provided Sql object must match the table of this TableGateway');
		$this->initialize();
	}
}