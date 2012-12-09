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
			if(isset(self::$logs[$this->logId]['errors'])){
				$aErrors = self::$logs[$this->logId]['errors'];
				unset(self::$logs[$this->logId]['errors']);
			}
			if(!$this->insert(array_merge(
				self::$logs[$this->logId],
				array('log_ending' => $oDateTime->format(\DateTime::ISO8601)))
			))throw new \Exception('Error occured during log insertion');
			
			$sRealLogId = $this->getLastInsertValue();
			if(!empty($aErrors))foreach($aErrors as $aErrorInfos){
				$aErrorInfos['log_id'] = $sRealLogId;
				if(!$this->sql->prepareStatementForSqlObject($this->sql->insert()->into('errors')
				->columns(array_keys($aErrorInfos))
				->values($aErrorInfos))->execute())throw new \Exception('Error occured during log insertion');
			}
		}
		unset(self::$logs[$this->logId]);
		$this->logId = null;
		return $this;
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
			case $oParam instanceof \Zend\Stdlib\RequestInterface:
				return $this->setRequest($oParam);
			case $oParam instanceof \Zend\Mvc\Router\Http\RouteMatch:
				return $this->setRouteMatch($oParam);
			case $oParam instanceof \Exception:
				return $this->setError($oParam,func_get_arg(3));
			default:
				error_log(get_class($oParam));
		}
	}

	/**
	 * @see \Logger\Service\Adapter\LogAdapterInterface::getLogId()
	 * @return int
	 */
	public function getLogId(){
		return $this->logId;
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
	 * Retrieve logged user id, if exists
	 * @return int|null
	 */
	protected function getUserId(){
		return (
			$this->authService instanceof \Zend\Authentication\AuthenticationService
			&& $this->authService->hasIdentity()
		)?$this->authService->getIdentity():null;
	}
}