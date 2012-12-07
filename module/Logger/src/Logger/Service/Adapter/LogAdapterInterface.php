<?php
namespace Logger\Service\Adapter;
interface LogAdapterInterface{
	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @param \Zend\Stdlib\RequestInterface $oRequest
	 * @return \Logger\Service\Adapter\LogAdapterInterface
	 */
	public function started($sCurrentId,$oDateTime,\Zend\Stdlib\RequestInterface $oRequest);

	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @return \Logger\Service\Adapter\LogAdapterInterface
	 */
	public function completed($sCurrentId,$oDateTime);

	/**
	 * @param string $sCurrentId
	 * @param \DateTime $oDateTime
	 * @param mixed $oParam
	 * @return \Logger\Service\Adapter\LogAdapterInterface
	 */
	public function log($sCurrentId,$oDateTime,$oParam);

	/**
	 * @return string|int
	 */
	public function getLogId();
}