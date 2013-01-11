<?php
namespace Application\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\TableGateway{
	/**
	 * Constructor
	 * @param string $sTable
	 * @param \Zend\Db\Adapter\Adapter $oAdapter
	 * @param \Application\Db\RowGateway\AbstractRowGateway $oEntity
	 */
    public function __construct($sTable, \Zend\Db\Adapter\Adapter $oAdapter, $sRowGatewayClass){
    	parent::__construct(
    		$sTable,
    		$oAdapter,
    		array(
    			new \Zend\Db\TableGateway\Feature\MetadataFeature(),
    			new \Application\Db\TableGateway\Feature\EventFeature(),
    			new \Application\Db\TableGateway\Feature\RowGatewayFeature($sRowGatewayClass)
    		)
    	);
	}

	/**
	 * @return array
	 */
	public function getPrimaryKey(){
		$oMetadataFeature = $this->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
		if($oMetadataFeature === false || !isset($oMetadataFeature->sharedData['metadata']))throw new \Exception('No MetadataFeature could be consulted');
		return (array)$oMetadataFeature->sharedData['metadata']['primaryKey'];
	}

	/**
	 * @param string $sValue
	 * @param string $sColumnName
	 * @throws \Exception
	 * @return mixed
	 */
	public function offsetFormatDataForEntity($sValue,$sColumnName){
		try{
			$oColumn = $this->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature')->sharedData['metadata']->getColumn($sColumnName, $this->getTable());
		}
		catch(\Exception $oException){
			if(method_exists($this, 'formatDataForEntity'))return $this->customDataToEntite($sValue,$sColumnName);
			else throw new \Exception('Unknown column name "'.$sColumnName.'"');
		}
		switch($sType = $oColumn->getDataType()){
			case 'float':
				return (float)$sValue;
			case 'tinyint':
				return (bool)$sValue;
			case 'varchar':
			case 'tinytext':
			case 'text':
				return (string)$sValue;
			case 'datetime':
			case 'timestamp':
				if(!($sValue instanceof \DateTime))$sValue = new \Datetime((string)$sValue);
				return $sValue;
			default:
				throw new \Exception('Mysql type "'.$sType.'" is not supported');
		}
	}

	/**
	 * @param string $sValue
	 * @param string $sColumnName
	 * @throws \Exception
	 * @return mixed
	 */
	public function offsetFormatDataForDb($sValue,$sColumnName){
		try{
			$oColumn = $this->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature')->sharedData['metadata']->getColumn($sColumnName, $this->getTable());
		}
		catch(\Exception $oException){
			if(method_exists($this, 'formatDataForDb'))return $this->customDataToEntite($sValue,$sColumnName);
			else throw new \Exception('Unknown column name "'.$sColumnName.'"');
		}
		switch($sType = $oColumn->getDataType()){
			case 'float':
				return (float)$sValue;
			case 'tinyint':
				return (int)!!$sValue;
			case 'varchar':
			case 'text':
			case 'tinytext':
				return (string)$sValue;
			case 'datetime':
				if(!($sValue instanceof \DateTime))$sValue = new \Datetime((string)$sValue);
				return $sValue->format(DATE_ISO8601);
			case 'timestamp':
				if(!($sValue instanceof \DateTime))$sValue = new \Datetime((string)$sValue);
				return $sValue->getTimestamp();
			default:
				throw new \Exception('Mysql type "'.$sType.'" is not supported');
		}
	}
}