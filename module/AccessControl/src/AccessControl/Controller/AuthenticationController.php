<?php
namespace AccessControl\Controller;
class AuthenticationController extends \Templating\Mvc\Controller\AbstractActionController{
	/**
	 * Show authenticate form or process authenticate attempt
	 * @throws \UnexpectedValueException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function authenticationAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('sign_in');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('LoginForm');

		$oFlashMessenger = $this->flashMessenger()->setNamespace(__CLASS__);
		if($oFlashMessenger->hasCurrentMessages()){
			$bReturn = current($oFlashMessenger->getCurrentMessages());
			$oFlashMessenger->clearCurrentMessages();
		}
		elseif((
			$this->params('service') &&
			($bReturn = $this->getServiceLocator()->get('AccessControlService')->login(
				\AccessControl\Service\AccessControlService::HYBRID_AUTH_AUTHENTICATION,
				$this->params('service')
			)) === true
		) ||
		(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('AccessControlService')->login(
				\AccessControl\Service\AccessControlService::LOCAL_AUTHENTICATION,
				$this->params()->fromPost('identity'),
				$this->params()->fromPost('credential')
			)) === true
		)){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		if(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \UnexpectedValueException('Authenticate process failed return type expects string, "'.gettype($bReturn).'" given');
		}

		//Try to define redirect url
		if(
			empty($this->getServiceLocator()->get('Session')->redirect)
			&& ($sHttpReferer = $this->getRequest()->getServer('HTTP_REFERER'))
			&& is_array($aInfosUrl = parse_url($sHttpReferer))
			&& $this->getRequest()->getServer('HTTP_HOST') === $aInfosUrl['host']
		)$this->getServiceLocator()->get('Session')->redirect = $sHttpReferer;

		return $this->view;
	}

	/**
	 * Process hybridauth request
	 * @return void|\Zend\Http\Response
	 */
	public function hybridauthAction(){
		if(($sProvider = $this->params()->fromQuery('hauth_done')) && $this->params()->fromQuery('error') === 'access_denied'){
			$this->flashMessenger()->setNamespace(__CLASS__)->addMessage(sprintf(
				$this->getServiceLocator()->get('translator')->translate('provider_authentification_canceled'),
				$sProvider
			));
			return $this->redirect()->toRoute('AccessControl/authentication');
		}
		\Hybrid_Endpoint::process();
	}

	/**
	 * Show forgotten password form, or process form submit request
	 * @throws \LogicException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function forgottenpasswordAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ResetPasswordForm');

		if($this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('AccessControlService')->sendConfirmationResetPassword($this->params()->fromPost('user_email'))) === true
		)$this->view->passwordReset = true;
		elseif(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \LogicException('Reset password process return expects string, "'.gettype($bReturn).'" given');
		}
		return $this->view;
	}

	/**
	 * Process reset password request
	 * @throws \LogicException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function resetpasswordAction(){
		if(!($sResetKey = $this->params('reset_key')))throw new \LogicException('Reset key param is missing');
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');
		$this->getServiceLocator()->get('AccessControlService')->resetPassword($sResetKey);
		return $this->view;
	}

	/**
	 * Logout user
	 * @throws \RuntimeException
	 */
	public function logoutAction(){
		if(!$this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()
		|| $this->getServiceLocator()->get('AccessControlService')->logout())return (
			//Try to define redirect url
			empty($this->getServiceLocator()->get('Session')->redirect)
			&& ($sHttpReferer = $this->getRequest()->getServer('HTTP_REFERER'))
			&& is_array($aInfosUrl = parse_url($sHttpReferer))
			&& $this->getRequest()->getServer('HTTP_HOST') === $aInfosUrl['host']
		)?$this->redirect()->toUrl($sHttpReferer):$this->redirect()->toRoute('home');
		else throw new \RuntimeException('Error occured during logout process');
	}
}