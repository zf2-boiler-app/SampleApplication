<?php
namespace User\Authentication\Adapter;
interface AuthenticationAdapterInterface extends \Zend\Authentication\Adapter\AdapterInterface{

	/**
	 * Initialization adapter
	 * @return \User\Authentication\Adapter\AuthenticationAdapterInterface
	 * @throws \Exception If initialization cannot be performed
	 */
	public function initialize();
}