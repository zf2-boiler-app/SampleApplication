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

	public function login($sUserEmail = null,$sUserPassword = null,$sService = \ZF2User\Authentication\AuthenticationService::AUTH_SERVICE_LOCAL){
		if(!is_string($sService))throw new \Exception('Authentication service ('.gettype($sService).') is not a string');

		$oAuthService = $this->getServiceManager()->get('AuthService');
		if(!($bIsLocalAuth = $sService === \ZF2User\Authentication\AuthenticationService::AUTH_SERVICE_LOCAL))$oAuthService->setHybridAuthAdapter(
			$this->getServiceManager()->get('HybridAuthAdapter')
		);
		elseif(!is_string($sUserEmail) || !is_string($sUserPassword))throw new \Exception('User\'s email ('.gettype($sUserEmail).') or user\'s ('.gettype($sUserPassword).') password are not strings');

		switch($iResult = $oAuthService->login($sUserEmail,$sUserPassword,$sService)){
			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_UNREGISTERED_USER:
				if($bIsLocalAuth)throw new \Exception('Invalid Authentication Service return code with local authentication service');
				return sprintf($this->getServiceManager()->get('translator')->translate('unknown_user'),$sService);

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG:
				return $this->getServiceManager()->get('translator')->translate('email_or_password_wrong');

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_USER_STATE_PENDING:
				return $this->getServiceManager()->get('translator')->translate('user_state_pending');

			case \ZF2User\Authentication\AuthenticationService::AUTH_RESULT_VALID:
				return true;

			default:
				throw new \Exception('Unknown Authentication Service return code : '.$iResult);
		}
	}

	public function hybridLogin($sService){



	}
}