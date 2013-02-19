<?php
namespace AccessControl\Service;
class AccessControlService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	const LOCAL_AUTHENTICATION = 'LocalAuth';
	const HYBRID_AUTH_AUTHENTICATION = 'HybridAuth';

	/**
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @return \User\Entity\UserEntity
	 */
	public function getLoggedUser(){
		$iUserId = $this->getServiceLocator()->get('AccessControlAuthenticationService')->getIdentity();

		//Prevent from session value error
		try{
			$oUser = $this->getServiceLocator()->get('User\Repository\User\Repository')->find($iUserId);
		}
		catch(\Exception $oException){
			$this->logout();
			throw new \RuntimeException('An error occurred when retrieving logged user');
		}
		if(!$oUser->isUserActive())throw new \LogicException('User is not active');
		return $oUser;
	}


	/**
	 * Login user
	 * @param string $sAdapterName
	 * @throws \InvalidArgumentException
	 * @return string|boolean
	 */
	public function login($sAdapterName){
		if(!is_string($sAdapterName))throw new \InvalidArgumentException('Adapter\'s name expects string, "'.gettype($sAdapterName).'" given');

		//Performs authentication attempt
		switch($iResult = call_user_func_array(
			array($this->getServiceLocator()->get('AccessControlAuthenticationService'),'authenticate'),
			func_get_args()
		)){
			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_VALID:
				return true;

			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_EMAIL_OR_PASSWORD_WRONG:
				return $this->getServiceLocator()->get('translator')->translate('email_or_password_wrong');

			case \AccessControl\Authentication\AccessControlAuthenticationService::AUTH_RESULT_USER_STATE_PENDING:
				return $this->getServiceLocator()->get('translator')->translate('user_state_pending');
			//Unknown error
			default:
				return $this->getServiceLocator()->get('translator')->translate($iResult);
		}
	}

	/**
	 * Log out current logged user
	 * @return \AccessControl\Service\AccessControlService
	 */
	public function logout(){
		$this->getServiceLocator()->get('AccessControlAuthenticationService')->clearIdentity();
		return $this;
	}
}