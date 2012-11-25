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

	public function getLoginForm(){
		if(isset($this->forms['login']))return $this->forms['login'];
		$this->forms['login'] = new \ZF2User\Form\Login();
		return $this->forms['login'];
	}
}