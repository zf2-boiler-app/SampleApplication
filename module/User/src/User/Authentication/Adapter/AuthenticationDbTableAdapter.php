<?php
namespace User\Authentication\Adapter;
class AuthenticationDbTableAdapter extends \Zend\Authentication\Adapter\DbTable implements \User\Authentication\Adapter\AuthenticationAdapterInterface{
	/**
	 * @return \User\Authentication\Adapter\AuthenticationDbTableAdapter
	 * @throws \Exception
	 */
	public function postAuthenticate($sIdentity,$sCredential){
		if(!is_string($sIdentity) || !is_string($sCredential))throw new \Exception('Identity ('.gettype($sIdentity).') and/or credential('.gettype($sCredential).') are not strings');
		return $this->setIdentity($sIdentity)->setCredential($sCredential);
	}
}