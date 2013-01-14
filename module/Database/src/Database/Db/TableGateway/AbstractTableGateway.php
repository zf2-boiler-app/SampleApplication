<?php
namespace Database\Db\TableGateway;
abstract class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway{
	/**
	 * @var array
	 */
	public $columnsInfos = array();


	/**
	 * @param \Zend\Db\Sql\Insert $oInsert
	 * @throws \Exception
	 * @return int
	 */
	protected function executeInsert(\Zend\Db\Sql\Insert $oInsert){
		$aInsertState = $oInsert->getRawState();
		if($aInsertState['table'] != $this->table)throw new \Exception('The table name of the provided Insert object must match that of the table');

		$aInsertData = array_combine($aInsertState['columns'],$aInsertState['values']);
		if($aUnknownColumns = array_diff_key($aInsertData,array_flip($this->columns)))throw new \Exception('Unkonow columns provided : '.join(', ',array_keys($aUnknownColumns)));


		// apply preInsert features
		$this->featureSet->apply('preInsert', array($oInsert));

		$oStatement = $this->sql->prepareStatementForSqlObject($oInsert);
		$oResult = $oStatement->execute();

		$iAffectedRows = $oResult->getAffectedRows();
		$this->lastInsertValue = $this->adapter->getDriver()->getConnection()->getLastGeneratedValue();

		$aInsertedId = array();
		$aPrimaryKeyColumn = array_flip($this->getResultSetPrototype()->getArrayObjectPrototype()->getPrimaryKeyColumn());
		$aInsertedIds = array_intersect_key($aInsertData,$aPrimaryKeyColumn);
		if(count($aPrimaryKeyColumn) > count($aInsertedIds)){
			$aNotAffectedKeys = array_diff_key($aPrimaryKeyColumn,$aInsertedIds);
			if(count($aNotAffectedKeys) === 1)$aInsertedIds[key($aNotAffectedKeys)] = $this->lastInsertValue;
			else throw new Exception('Unable to retrieve primary key');
		}

		// apply postInsert features
		$this->featureSet->apply('postInsert', array($oStatement, $oResult,$aInsertedIds));

		//Clean up
		unset($oStatement,$oResult,$aPrimaryKeyColumn,$aInsertedIds);

		return $iAffectedRows;
	}

	/**
	 * @param \Zend\Db\Sql\Update $oUpdate
	 * @throws \Exception
	 * @return int
	 */
	protected function executeUpdate(\Zend\Db\Sql\Update $oUpdate){
		//Add entity_update value
		if(in_array('entity_update',$this->columns))$oUpdate->set(array('entity_update' => new \Zend\Db\Sql\Predicate\Expression('NOW()')),\Zend\Db\Sql\Update::VALUES_MERGE);

		$aUpdateState = $oUpdate->getRawState();
		if($aUpdateState['table'] != $this->table)throw new \Exception('The table name of the provided Update object must match that of the table');
		if($aUnknownColumns = array_diff_key($aUpdateState['set'],array_flip($this->columns)))throw new \Exception('Unkonow columns provided : '.join(', ',array_keys($aUnknownColumns)));

		//Apply preUpdate features
		$this->featureSet->apply('preUpdate', array($oUpdate));

		$oStatement = $this->sql->prepareStatementForSqlObject($oUpdate);

		//Retrieve updated ids
		$aUpdatedIds = $this->selectWith($this->sql->select()->columns($this->getResultSetPrototype()->getArrayObjectPrototype()->getPrimaryKeyColumn())->where($aUpdateState['where']));

		//Execute update
		$oResult = $oStatement->execute();
		$iAffectedRows = $oResult->getAffectedRows();

		//Apply postUpdate features
		$this->featureSet->apply('postUpdate', array($oStatement, $oResult,$aUpdatedIds));

		//Clean up
		unset($oStatement,$oResult,$aUpdatedIds);

		return $iAffectedRows;
	}

	/**
	 * @param \Zend\Db\Sql\Delete $delete
	 * @throws \Exception
	 * @return int
	 */
	protected function executeDelete(\Zend\Db\Sql\Delete $oDelete){
		$aDeleteState = $oDelete->getRawState();
		if($aDeleteState['table'] != $this->table)throw new \Exception('The table name of the provided Delete object must match that of the table');
		if($aUnknownColumns = array_diff_key($aDeleteState['set'],array_flip($this->columns)))throw new \Exception('Unkonow columns provided : '.join(', ',array_keys($aUnknownColumns)));

		//Pre delete update
		$this->featureSet->apply('preDelete', array($oDelete));

		$oStatement = $this->sql->prepareStatementForSqlObject($oDelete);

		//Retrieve updated ids
		$aDeletedIds = $this->selectWith($this->sql->select()->columns($this->getResultSetPrototype()->getArrayObjectPrototype()->getPrimaryKeyColumn())->where($aDeleteState['where']));

		$oResult = $oStatement->execute();
		$iAffectedRows = $oResult->getAffectedRows();

		//Apply postDelete features
		$this->featureSet->apply('postDelete', array($oStatement, $oResult,$aDeletedIds));

		//Clean up
		unset($oStatement,$oResult,$aDeletedIds);

		return $iAffectedRows;
	}




	/**
	 * @param string $sValue
	 * @param string $sColumnName
	 * @throws \Exception
	 * @return mixed
	 */
	public function offsetFormatDataForEntity($sValue,$sColumnName){
		if($oColumn = $this->getColumnInfo($sColumnName))switch($sType = $oColumn->getDataType()){
			case 'int':
				return (int)$sValue;
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
			case 'enum':
				$aPermittedValues = $oColumn->getErrata('permitted_values');
				if(is_null($aPermittedValues))throw new \Exception($sColumnName.' has no permitted values');
				if(in_array($sValue,$aPermittedValues))return $sValue;
				throw new \Exception(sprintf(
					'%s expects "%s", %s given',
					$sColumnName,join(', ',$aPermittedValues),
					is_string($sValue)?$sValue:gettype($sValue)
				));
			default:
				throw new \Exception('Mysql type "'.$sType.'" is not supported');
		}
		if(method_exists($this, 'formatDataForEntity'))return $this->formatDataForEntity($sValue,$sColumnName);
		else throw new \Exception('Unknown column name "'.$sColumnName.'"');
	}

	/**
	 * @param string $sValue
	 * @param string $sColumnName
	 * @throws \Exception
	 * @return mixed
	 */
	public function offsetFormatDataForDb($sValue,$sColumnName){
		if($oColumn = $this->getColumnInfo($sColumnName))switch($sType = $oColumn->getDataType()){
			case 'int':
				return (int)$sValue;
			case 'float':
				return (float)$sValue;
			case 'tinyint':
				return (int)!!$sValue;
			case 'varchar':
			case 'text':
			case 'tinytext':
				return (string)$sValue;
			case 'datetime':
			case 'timestamp':
				if(!($sValue instanceof \DateTime))$sValue = new \Datetime((string)$sValue);
				return $sValue->format(DATE_ISO8601);
			case 'enum':
				$aPermittedValues = $oColumn->getErrata('permitted_values');
				if(is_null($aPermittedValues))throw new \Exception($sColumnName.' has no permitted values');
				if(in_array($sValue,$aPermittedValues))return $sValue;
				throw new \Exception(sprintf(
					'%s expects "%s", %s given',
					$sColumnName,join(', ',$aPermittedValues),
					is_string($sValue)?$sValue:gettype($sValue)
				));
			default:
				throw new \Exception('Mysql type "'.$sType.'" is not supported');
		}
		if(method_exists($this, 'formatDataForDb'))return $this->formatDataForDb($sValue,$sColumnName);
		else throw new \Exception('Unknown column name "'.$sColumnName.'"');
	}

	/**
	 * @param string $sColumnName
	 * @throws \Exception
	 * @return \Zend\Db\Metadata\Object\ColumnObject
	 */
	public function getColumnInfo($sColumnName){
		if(!is_array($this->columnsInfos))throw new \Exception('Columns infos are undefined');
		return isset($this->columnsInfos[$sColumnName])?$this->columnsInfos[$sColumnName]:false;
	}
}