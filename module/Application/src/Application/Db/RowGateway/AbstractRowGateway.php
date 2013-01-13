<?php
namespace Application\Db\RowGateway;
abstract class AbstractRowGateway extends \Zend\Db\RowGateway\RowGateway{
	/**
	 * @var \Application\Db\TableGateway\AbstractTableGateway
	 */
	protected $model;

	/**
	 * Constructor
	 * @param array $aPrimaryKey
	 * @param \Application\Db\TableGateway\AbstractTableGateway $oModel
	 */
	public function __construct(array $aPrimaryKey,\Application\Db\TableGateway\AbstractTableGateway $oModel){
		$this->model = $oModel;
		$this->featureSet = new \Zend\Db\RowGateway\Feature\FeatureSet(array(
			new \Application\Db\RowGateway\Feature\EventFeature()
		));
		parent::__construct($aPrimaryKey, $oModel->getTable(),$oModel->getAdapter());
	}

	/**
	 * @see \Zend\Db\RowGateway\AbstractRowGateway::__get()
	 * @param string $sColumnName
	 * @return mixed
	 */
	public function __get($sColumnName){
		return method_exists($this,$sMethod = 'get'.self::getMethodFromColumn($sColumnName))?$this->$sMethod():parent::__get($sColumnName);
	}

	/**
	 * @see \Zend\Db\RowGateway\AbstractRowGateway::__set()
	 * @param string $sColumnName
	 * @param mixed $sValue
	 */
	public function __set($sColumnName, $sValue){
		if(method_exists($this,$sMethod = 'set'.self::getMethodFromColumn($sColumnName)))$this->$sMethod($sValue);
		else parent::__set($sColumnName, $sValue);
	}

	public function __call($sMethodName, $aArguments){
		if(preg_match('/^get([A-Z]{1}[a-zA-Z]+$)/', $sMethodName,$aMatches))return $this->__get(self::getColumnFromMethod($aMatches[1]));
		throw new \Exception('Undefined method '.$sMethodName);
	}

	/**
	 * @return array
	 */
	public function getPrimaryKeyColumn(){
		return $this->primaryKeyColumn;
	}

	/**
	 * Save
	 * @return integer
	 */
	public function save(){
		$this->initialize();
		if(!$this->rowExistsInDatabase())throw new \Exception('Save method is not allowed to insert rows');

		$aData = $this->data;
		$aWhere = array();

		//Primary key is always an array even if its a single column
		foreach($this->primaryKeyColumn as $sPkColumn){
			$aWhere[$sPkColumn] = $this->primaryKeyData[$sPkColumn];
			if($aData[$sPkColumn] == $this->primaryKeyData[$sPkColumn])unset($aData[$sPkColumn]);
		}

		$aKeys = array_keys($aData);

		$oUpdate = $this->sql->update()
		->set(array_combine($aKeys,array_map(array($this->model,'offsetFormatDataForDb'),$aData,$aKeys)))
		->where($aWhere);

		//Add entity_update value
		if(in_array('entity_update',$this->model->getColumns()))$oUpdate->set(array('entity_update' => new \Zend\Db\Sql\Predicate\Expression('NOW()')),\Zend\Db\Sql\Update::VALUES_MERGE);

		//Apply preSave features
		$this->featureSet->apply('preSave', array($oUpdate));

		//Update row data
		$oStatement = $this->sql->prepareStatementForSqlObject($oUpdate);
		$oResult = $oStatement->execute();
		$iRowsAffected = $oResult->getAffectedRows();

		//Apply postSave features
		$this->featureSet->apply('postSave', array($oStatement, $oResult,array($aWhere)));

		//Clean up
		unset($oUpdate,$oStatement,$oResult);

		//Make sure data and original data are in sync after save
		$this->populate(
			$this->sql->prepareStatementForSqlObject($this->sql->select()->where($aWhere))->execute()->current(),
			true
		);
		return $iRowsAffected;
	}

	/**
	 * Delete
	 */
	public function delete(){
		$this->initialize();
		if(!$this->rowExistsInDatabase())throw new \Exception('Delete method is not allowed to non inserted rows');

		$aWhere = array();

		//Primary key is always an array even if its a single column
		foreach($this->primaryKeyColumn as $sPkColumn){
			$aWhere[$sPkColumn] = $this->primaryKeyData[$sPkColumn];
		}

		$oDelete = $this->sql->delete()->where($aWhere);

		//Apply preSave features
		$this->featureSet->apply('preDelete', array($oDelete));

		$oStatement = $this->sql->prepareStatementForSqlObject($oDelete);
		$oResult = $oStatement->execute();

		// detach from database
		if($oResult->getAffectedRows() == 1)$this->primaryKeyData = null;
		else throw new \Exception('No rows have been deleted');

		//Apply postSave features
		$this->featureSet->apply('postDelete', array($oStatement, $oResult,array($aWhere)));

		//Clean up
		unset($oUpdate,$oStatement,$oResult);


	}

	/**
	 * Populate Data
	 * @param  array $rowData
	 * @param  bool  $rowExistsInDatabase
	 * @return AbstractRowGateway
	 */
	public function populate(array $aRowData, $bRowExistsInDatabase = false){
		$this->initialize();
		$aKeys = array_keys($aRowData);
		$this->data = array_combine($aKeys,array_map(array($this->model,'offsetFormatDataForEntity'),$aRowData,$aKeys));
		if($bRowExistsInDatabase == true)$this->processPrimaryKeyData();
		else $this->primaryKeyData = null;
		return $this;
	}

	/**
	 * @param string $sMethodName
	 * @return string
	 */
	private static function getColumnFromMethod($sMethodName){
		return ltrim(strtolower(preg_replace('/([A-Z])/','_$1',$sMethodName)),'_');
	}

	/**
	 * @param string $sColumnName
	 * @return string
	 */
	private static function getMethodFromColumn($sColumnName){
		return ucfirst(join('',array_map(function($sPart){
			return ucfirst($sPart);
		}, explode('_',$sColumnName))));
	}
}