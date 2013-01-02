<?php
namespace Logger\Service\Adapter;
class DbLogAdapter extends \Zend\Db\TableGateway\TableGateway implements \Logger\Service\Adapter\LogAdapterInterface{
	/**
	 * @var int
	 */
	protected $logId;

	/**
	 * @var \Zend\Authentication\AuthenticationService
	 */
	protected $authService;

	protected static $logs = array();

	/**
	 * @param \Zend\Authentication\AuthenticationService $oAuthService
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	public function setAuthService(\Zend\Authentication\AuthenticationService $oAuthService){
		$this->authService = $oAuthService;
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
			$this->authService instanceof \Zend\Authentication\AuthenticationService
			&& $this->authService->hasIdentity()
		)?$this->authService->getIdentity():null;
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
				if(func_num_args() !== 6)throw new \Exception('Entity log expects 6 params, '.func_num_args().' given');
				return $this->setEntity($oParam,func_get_arg(3),func_get_arg(4),func_get_arg(5));
			default:
				error_log('log : '.get_class($oParam));
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
			if(!empty($aEntities))foreach($aEntities as $aEntityInfos){
				if(!isset($aEntityInfos['entity_id'],$aEntityInfos['entity_table'],$aEntityInfos['entity_primary'],$aEntityInfos['entity_action'])
					|| !(
						(is_int($aEntityInfos['entity_id']) && is_string($aEntityInfos['entity_primary']))
						|| (is_array($aEntityInfos['entity_id']) && is_array($aEntityInfos['entity_primary']))
					)
					|| !is_string($aEntityInfos['entity_table'])
					|| !is_string($aEntityInfos['entity_action'])
				)throw new \Exception('Entity infos are invalid');
					
				//Prepare statement for insert or update entity log
				switch($aEntityInfos['entity_action']){
					case \Logger\Service\LoggerService::LOG_EVENT_CREATE_ENTITY:
					case \Logger\Service\LoggerService::LOG_EVENT_DELETE_ENTITY:
						$oStatement = $this->sql->update()
						->set(array('entity_log_id' => $sRealLogId));
						if(is_string($aEntityInfos['entity_primary']))$oStatement->where(array($aEntityInfos['entity_primary'] => $aEntityInfos['entity_id']));
						else foreach($aEntityInfos['entity_primary'] as $iKey => $sEntityPrimary){
							$oStatement->where(array($sEntityPrimary => $aEntityInfos['entity_id'][$iKey]));
						}
						if($aEntityInfos['entity_action'] === \Logger\Service\LoggerService::LOG_EVENT_DELETE_ENTITY)$oStatement->where(array(
							'entity_deleted' => true
						));
						break;
					case \Logger\Service\LoggerService::LOG_EVENT_UPDATE_ENTITY:
						if(!isset($aEntityInfos['entity_infos']) || !is_array($aEntityInfos['entity_infos']))throw new \Exception('Entity infos are undefined or value is not an array');
						$oStatement = $this->sql->insert()->values(array_merge(
							array('entity_log_id' => $sRealLogId),
							$aEntityInfos['entity_infos']
						));
						break;
					default:
						throw new \Exception('Entity action "'.$sEntityAction.'" is not valid');
				}
				
				//Execute statement
				if(!$this->sql->prepareStatementForSqlObject($oStatement->table(self::getEntityLogTable($aEntityInfos['entity_table'])))->execute())throw new \Exception('Error occured during log insertion');
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
		self::$logs[$this->logId] = array_merge(self::$logs[$this->logId],array(
			'log_request_uri' => $oRequest->getRequestUri(),
			'log_request_method' => $oRequest->getMethod(),
			'log_user_agent' => $oRequest->getHeader('User-Agent')->getFieldValue(),
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
	 * @param int $iEntityId
	 * @param string $sEntityTable
	 * @param string|array $sEntityPrimary
	 * @param string $sEntityAction
	 * @throws \Exception
	 * @return \Logger\Service\Adapter\DbLogAdapter
	 */
	protected function setEntity($iEntityId, $sEntityTable, $sEntityPrimary, $sEntityAction){
		if(!(
			(is_int($iEntityId) && is_string($sEntityPrimary))
			|| (is_array($iEntityId) && is_array($sEntityPrimary))
		) || !is_string($sEntityAction) || !is_string($sEntityTable))throw new \Exception('Entity params are invalid');
		if(!isset(self::$logs[$this->logId]))throw new \Exception('Log "'.$this->logId.'" is not initialized');
		if(!isset(self::$logs[$this->logId]['entities']))self::$logs[$this->logId]['entities'] = array();
		$aLogEntityParams = array(
			'entity_id' => $iEntityId,
			'entity_table' => $sEntityTable,
			'entity_primary' => $sEntityPrimary,
			'entity_action' => $sEntityAction,
		);
		
		if($sEntityAction === \Logger\Service\LoggerService::LOG_EVENT_UPDATE_ENTITY){
			//Prepare statement to retrieve entity infos
			$oStatement = $this->sql->select()->from($sEntityTable);
			if(is_string($sEntityPrimary))$oStatement->where(array($sEntityPrimary => $iEntityId));
			else foreach($sEntityPrimary as $iKey => $sEntityPrimaryColumn){
				$oStatement->where(array($sEntityPrimaryColumn => $iEntityId[$iKey]));
			}
			//Execute statement
			if(!($aLogEntityParams['entity_infos'] = $this->sql->prepareStatementForSqlObject($oStatement)->execute()))throw new \Exception('Error occurred during the retrieval entity\'s infos.');
		}
		self::$logs[$this->logId]['entities'][] = $aLogEntityParams;
		return $this;
	}
}