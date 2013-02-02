<?php
namespace User\Authentication\Adapter;
interface AuthenticationAdapterInterface extends \Zend\Authentication\Adapter\AdapterInterface{
	/**
	 * Returns the result row as a stdClass object
	 * @param string|array $aReturnColumns
	 * @param string|array $aOmitColumns
	 * @return stdClass|boolean
	 */
	public function getResultRowObject($aReturnColumns = null, $aOmitColumns = null);
}