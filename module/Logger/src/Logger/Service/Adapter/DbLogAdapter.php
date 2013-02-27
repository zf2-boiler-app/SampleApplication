<?php
namespace Logger\Service\Adapter;
class DbLogAdapter extends \Zend\Db\TableGateway\TableGateway implements \Logger\Service\Adapter\LogAdapterInterface{
	/**
	 * @var int
	 */
	protected $logId;

	/**
	 * @var \User\Authentication\UserAuthenticationService
	 */
	protected $userAuthenticationService;

	protected static $logs = array();

	/**
	 * @param \User\Authentication\UserAuthenticationService $oUserAuthenticationService
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	public function setUserAuthenticationService(\User\Authentication\UserAuthenticationService $oUserAuthenticationService){
		$this->userAuthenticationService = $oUserAuthenticationService;
		return $this;
	}

	/**
	 * @see \Logger\Service\Adapter\LogAdapterInterface::getLogId()
	 * @return int
	 */
	public function getLogId(){
		return $this->logId;
	}

	/**
	 * Retrieve logged user id, if exists
	 * @return int|null
	 */
	protected function getUserId(){
		return (
			$this->userAuthenticationService instanceof \User\Authentication\UserAuthenticationService
			&& $this->userAuthenticationService->hasIdentity()
		)?$this->userAuthenticationService->getIdentity():null;
	}

	protected static function getEntityLogTable($sEntityTable){
		if(empty($sEntityTable) || !is_string($sEntityTable))throw new \Exception('Entity table must me a not empty string');
		return $sEntityTable.'_logs';
	}

	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @param mixed $oParam
	 * @see \Logger\Service\Adapter\LogAdapterInterface::log()
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	public function log($sCurrentId,$oDateTime,$oParam){
		$this->logId = $sCurrentId;
		switch(true){
			//Request
			case $oParam instanceof \Zend\Stdlib\RequestInterface:
				return $this->setRequest($oParam);
			//Route
			case $oParam instanceof \Zend\Mvc\Router\Http\RouteMatch:
				return $this->setRouteMatch($oParam);
			//Exception
			case $oParam instanceof \Exception:
				return $this->setError($oParam,func_get_arg(3));
			//Entity
			case is_int($oParam):
			case is_array($oParam):
				if(func_num_args() !== 6)throw new \Exception('Entity log expects 6 params, '.func_num_args().' given');
				return $this->setEntity($oParam,func_get_arg(3),func_get_arg(4),func_get_arg(5));
			default:
				throw new \Exception('Unexpected log param : '.gettype($oParam));
		}
	}

	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @param \Zend\Http\PhpEnvironment\Request $oRequest
	 * @see \Logger\Service\Adapter\LogAdapterInterface::completed()
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	public function started($sCurrentId,$oDateTime,\Zend\Stdlib\RequestInterface $oRequest){
		$this->logId = $sCurrentId;
		if(isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is already initialized');
		self::$logs[$this->logId] = array('log_creation' => $oDateTime->format(\DateTime::ISO8601));
		return $this->setRequest($oRequest);
	}


	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @see \Logger\Service\Adapter\LogAdapterInterface::completed()
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	public function completed($sCurrentId,$oDateTime){
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		else{
			//Errors
			if(isset(self::$logs[$this->logId]['errors'])){
				$aErrors = self::$logs[$this->logId]['errors'];
				unset(self::$logs[$this->logId]['errors']);
			}

			//Entities
			if(isset(self::$logs[$this->logId]['entities'])){
				$aEntities = self::$logs[$this->logId]['entities'];
				unset(self::$logs[$this->logId]['entities']);
			}

			if(!$this->insert(array_merge(
				self::$logs[$this->logId],
				array('log_ending' => $oDateTime->format(\DateTime::ISO8601)))
			))throw new \Exception('Error occured during log insertion');

			//Retrieve real log id (inserted in database)
			$sRealLogId = $this->getLastInsertValue();

			//Insert error logs
			if(!empty($aErrors))foreach($aErrors as $aErrorInfos){
				$aErrorInfos['error_log_id'] = $sRealLogId;
				if(!$this->sql->prepareStatementForSqlObject(
					$this->sql->insert()->into('errors')
					->values($aErrorInfos))->execute()
				)throw new \Exception('Error occured during log insertion');
			}

			//Insert / entity logs
			if(!empty($aEntities))foreach($aEntities as $aEntity){
				if(
					!isset($aEntity['entity_id'],$aEntity['entity_table'],$aEntity['entity_primary'],$aEntity['entity_action'])
					|| !is_string($aEntity['entity_table'])
					|| !is_string($aEntity['entity_action'])
					|| !is_array($aEntity['entity_id'])
					|| !is_array($aEntity['entity_primary'])
				)throw new \Exception('Entity infos are invalid');

				//Prepare statement for insert or update entity log
				switch($aEntity['entity_action']){
					case \Logger\Service\LoggerService::LOG_EVENT_CREATE_ENTITY:
						$oStatement = $this->sql->update()
						->set(array('entity_log_id' => $sRealLogId));
						foreach($aEntity['entity_primary'] as $sEntityPrimary){
							$oStatement->where(array($sEntityPrimary => $aEntity['entity_id'][$sEntityPrimary]));
						}
						//Execute statement
						if(!$this->sql->prepareStatementForSqlObject($oStatement->table(self::getEntityLogTable($aEntity['entity_table'])))->execute())throw new \Exception('Error occured during log insertion');
						break;

					case \Logger\Service\LoggerService::LOG_EVENT_DELETE_ENTITY:
						$oStatement = $this->sql->update()
						->set(array('entity_log_id' => $sRealLogId))
						->where(array('entity_deleted' => true));

						if(count($aEntity['entity_primary']) === 1){
							$sEntityPrimary = current($aEntity['entity_primary']);
							$oStatement->where(array($sEntityPrimary => array_map(function($aEntityIdInfos) use($sEntityPrimary){
								if(!isset($aEntityIdInfos[$sEntityPrimary]))throw new \Exception('Entity id is not provided');
								return $aEntityIdInfos[$sEntityPrimary];
							},$aEntity['entity_id'])));
						}
						else{
							$oGlobalPredicate = new \Zend\Db\Sql\Predicate\PredicateSet();
							foreach($aEntity['entity_id'] as $aEntityIdInfos){
								$oPredicate =  new \Zend\Db\Sql\Predicate\PredicateSet();
								foreach($aEntity['entity_primary'] as $sEntityPrimary){
									$oPredicate->andPredicate(
										new \Zend\Db\Sql\Predicate\Operator($sEntityPrimary, Predicate\Operator::OP_EQ, $aEntityIdInfos[$sEntityPrimary])
									);
								}
								$oGlobalPredicate->orPredicate($oPredicate);
							}
							$oStatement->where($oGlobalPredicate);
						}

						//Execute statement
						if(!$this->sql->prepareStatementForSqlObject($oStatement->table(self::getEntityLogTable($aEntity['entity_table'])))->execute())throw new \Exception('Error occured during log insertion');
						break;

					case \Logger\Service\LoggerService::LOG_EVENT_UPDATE_ENTITY:
						if(!isset($aEntity['entity_infos']) || !($aEntity['entity_infos'] instanceof \Zend\Db\ResultSet\ResultSetInterface))throw new \Exception(sprintf(
							'Entity infos are undefined or value is not an instance of \Zend\Db\ResultSet\ResultSetInterface : %s',
							isset($aEntity['entity_infos'])?gettype($aEntity['entity_infos']):'not set'
						));

						//Create statement
						$oStatement = $this->sql->insert()->into(self::getEntityLogTable($aEntity['entity_table']));
						foreach($aEntity['entity_infos'] as $oEntityInfos){
							//Execute statement
							if(!$this->sql->prepareStatementForSqlObject($oStatement->values(array_merge(
								array('entity_log_id' => $sRealLogId),
								$oEntityInfos->getArrayCopy()
							)))->execute())throw new \Exception('Error occured during log insertion');
						}
						break;
					default:
						throw new \Exception('Entity action "'.$sEntityAction.'" is not valid');
				}
			}
		}
		unset(self::$logs[$this->logId]);
		$this->logId = null;
		return $this;
	}

	/**
	 * Add request infos to the current log
	 * @param \Zend\Stdlib\RequestInterface $oRequest
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	protected function setRequest(\Zend\Stdlib\RequestInterface $oRequest){
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		/* @var $oRequest \Zend\Http\Request */
		if($oRequest instanceof \Zend\Http\Request)self::$logs[$this->logId] = array_merge(self::$logs[$this->logId],array(
			'log_request_uri' => $oRequest->getRequestUri(),
			'log_request_method' => $oRequest->getMethod(),
			'log_user_agent' => $oRequest->getHeaders()->has('User-Agent')?$oRequest->getHeader('User-Agent')->getFieldValue():'',
			'log_is_ajax' => $oRequest->isXmlHttpRequest(),
			'log_user_id' => $this->getUserId()
		));
		return $this;
	}

	/**
	 * Add route match infos to the current log
	 * @param \Zend\Mvc\Router\Http\RouteMatch $oRouteMatch
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	protected function setRouteMatch(\Zend\Mvc\Router\Http\RouteMatch $oRouteMatch){
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		self::$logs[$this->logId] = array_merge(self::$logs[$this->logId],array(
			'log_route_name' => $oRouteMatch->getMatchedRouteName(),
			'log_controller_name' => $oRouteMatch->getParam('controller'),
			'log_action_name' => $oRouteMatch->getParam('action')
		));
		return $this;
	}

	/**
	 * Add error infos to the current log
	 * @param \Zend\Mvc\Router\Http\RouteMatch $oRouteMatch
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	protected function setError(\Exception $oException, $sError){
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		if(!isset(self::$logs[$this->logId]['errors']))self::$logs[$this->logId]['errors'] = array();
		self::$logs[$this->logId]['errors'][] = array(
			'error_name' => $sError,
			'error_exception' => $oException->__toString()
		);
		return $this;
	}

	/**
	 * Add entity infos to the current log
	 * @param int|array $aEntityId
	 * @param string $sEntityTable
	 * @param string|array $aEntityPrimary
	 * @param string $sEntityAction
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	protected function setEntity(array $aEntityId, $sEntityTable,array $aEntityPrimary, $sEntityAction){
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		if(!is_string($sEntityAction) || !is_string($sEntityTable) || !is_array($aEntityId) || !is_array($aEntityPrimary))throw new \Exception('Entity params are invalid');
		if(!isset(self::$logs[$this->logId]['entities']))self::$logs[$this->logId]['entities'] = array();

		$aLogEntityParams = array(
			'entity_id' => $aEntityId,
			'entity_table' => $sEntityTable,
			'entity_primary' => $aEntityPrimary,
			'entity_action' => $sEntityAction,
		);

		switch($sEntityAction){
			case \Logger\Service\LoggerService::LOG_EVENT_CREATE_ENTITY:
			case \Logger\Service\LoggerService::LOG_EVENT_DELETE_ENTITY:
				break;
			case \Logger\Service\LoggerService::LOG_EVENT_UPDATE_ENTITY:
				//Prepare statement to retrieve entity infos
				$oSelect = new \Zend\Db\Sql\Select($sEntityTable);
				if(count($aEntityPrimary) === 1){
					$sEntityPrimary = current($aEntityPrimary);
					$oSelect->where(array($sEntityPrimary => array_map(function($aEntityIdInfos) use($sEntityPrimary){
						if(!isset($aEntityIdInfos[$sEntityPrimary]))throw new \Exception('Entity id is not provided');
						return $aEntityIdInfos[$sEntityPrimary];
					},$aEntityId)));
				}
				else{
					$oGlobalPredicate = new \Zend\Db\Sql\Predicate\PredicateSet();
					foreach($aEntityId as $aEntityIdInfos){
						$oPredicate =  new \Zend\Db\Sql\Predicate\PredicateSet();
						foreach($aEntityPrimary as $sEntityPrimary){
							$oPredicate->andPredicate(
								new \Zend\Db\Sql\Predicate\Operator($sEntityPrimary, Predicate\Operator::OP_EQ, $aEntityIdInfos[$sEntityPrimary])
							);
						}
						$oGlobalPredicate->orPredicate($oPredicate);
					}
					$oSelect->where($oGlobalPredicate);
				}
				//Prepare statement & retrieve result
				if(
					($oResult = $this->sql->prepareStatementForSqlObject($oSelect)->execute()) instanceof \Zend\Db\Adapter\Driver\ResultInterface
					&& $oResult->isQueryResult()
					&& ($oResultSet = new \Zend\Db\ResultSet\ResultSet())
					&& $oResultSet->initialize($oResult)->count()
				)$aLogEntityParams['entity_infos'] = $oResultSet;
				else throw new \Exception('Error occurred during the retrieval entity\'s infos.');
				break;
			default:
				throw new \Exception('Entity action "'.$sEntityAction.'" is not valid');
		}
		self::$logs[$this->logId]['entities'][] = $aLogEntityParams;
		return $this;
	}
}