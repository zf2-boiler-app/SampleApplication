<?php
namespace Application\Db\RowGateway;
abstract class AbstractRowGateway extends \Zend\Db\RowGateway\RowGateway{
	/**
	 * @var \Application\Db\TableGateway\AbstractTableGateway
	 */
	protected $model;

	/**
	 * Constructor
	 * @param \Application\Db\TableGateway\AbstractTableGateway $oModel
	 */
	public function __construct(\Application\Db\TableGateway\AbstractTableGateway $oModel){
		$this->model = $oModel;
		parent::__construct($oModel->getPrimaryKey(),$oModel->getTable(),$oModel->getAdapter());
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

		//Update row datas
		$iRowsAffected = $this->sql->prepareStatementForSqlObject(
			$this->sql->update()
			->set(array_map(array($this->model,'offsetFormatDataFromEntity'),$aData,array_keys($aData)))
			->where($aWhere)
		)->execute()->getAffectedRows();

		//Make sure data and original data are in sync after save
		$this->populate(
			$this->sql->prepareStatementForSqlObject($this->sql->select()->where($aWhere))->execute()->current(),
			true
		);
		return $iRowsAffected;
	}

	/**
	 * Populate Data
	 * @param  array $rowData
	 * @param  bool  $rowExistsInDatabase
	 * @return AbstractRowGateway
	 */
	public function populate(array $aRowData, $bRowExistsInDatabase = false){
		$this->initialize();
		$this->data = array_map(array($this->model,'offsetFormatDataForEntity'),$aRowData,array_keys($aRowData));
		if($bRowExistsInDatabase == true)$this->processPrimaryKeyData();
		else $this->primaryKeyData = null;
		return $this;
	}

	/**
	 * @param string $sMethodName
	 * @return string
	 */
	private static function getColumnFromMethod($sMethodName){
		return ltrim(strtolower(preg_replace('','_$1',$sMethodName)),'_');
	}

	/**
	 * @param string $sColumnName
	 * @return string
	 */
	private static function getMethodFromColumn($sColumnName){
		return ucfirst(join('',array_map(function($sPart){
			return ucfirst($sPart);
		}), explode('_',$sColumnName)));
	}
}