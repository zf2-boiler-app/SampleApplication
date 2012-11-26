<?php
namespace ZF2User\Service;
class UserService implements \Zend\ServiceManager\ServiceManagerAwareInterface{
	/**
	 * @var \Zend\ServiceManager\ServiceManager
	 */
	private $serviceManager;

	/**
	 * @var array
	 */
	private $forms;

	public function setServiceManager(\Zend\ServiceManager\ServiceManager $oServiceManager){
		$this->serviceManager = $oServiceManager;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceManager(){
		if($this->serviceManager instanceof \Zend\ServiceManager\ServiceManager)return $this->serviceManager;
		throw new \Exception('Service Manager is undefined');
	}

	public function getLoginForm(){
		if(isset($this->forms['login']))return $this->forms['login'];
		$this->forms['login'] = new \ZF2User\Form\Login();
		return $this->forms['login']->prepare();
	}

	public function login($sUserEmail,$sUserPassword){
		if(!is_string($sUserEmail) || !is_string($sUserPassword))throw new \Exception('User\'s email ('.gettype($sUserEmail).') or user\'s ('.gettype($sUserPassword).') password are not strings');
		/* @var $oAuthResult \Zend\Authentication\Result */
		$oAuthResult = $this->getServiceManager()->get('AuthAdapter')
		->setIdentity($sUserEmail)
    	->setCredential($sUserPassword)
		->authenticate();
		if($oAuthResult->isValid())return true;
		else{
			error_log($oAuthResult->getCode());
			return false;
		}
	}
}