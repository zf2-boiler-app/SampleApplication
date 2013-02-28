<?php
namespace AccessControl\Controller;
class AuthenticationController extends \Templating\Mvc\Controller\AbstractActionController{
	/**
	 * Show authenticate form or process authenticate attempt
	 * @throws \UnexpectedValueException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function authenticateAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('Home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('sign_in');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('AuthenticateForm');

		$oFlashMessenger = $this->flashMessenger()->setNamespace(__CLASS__);
		if($oFlashMessenger->hasCurrentMessages()){
			$bReturn = current($oFlashMessenger->getCurrentMessages());
			$oFlashMessenger->clearCurrentMessages();
		}
		elseif((
			$this->params('service') &&
			($bReturn = $this->getServiceLocator()->get('AuthenticationService')->authenticate(
				\AccessControl\Service\AuthenticationService::HYBRID_AUTH_AUTHENTICATION,
				$this->params('service')
			)) === true
		) ||
		(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('AuthenticationService')->authenticate(
				\AccessControl\Service\AuthenticationService::LOCAL_AUTHENTICATION,
				$this->params()->fromPost('auth_access_identity'),
				$this->params()->fromPost('auth_access_credential')
			)) === true
		)){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('Home')
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
	 * Show Reset credential form, or process form submit request
	 * @throws \LogicException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function forgottenCredentialAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ResetCredentialForm');

		if($this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('AuthenticationService')->sendConfirmationResetCredential($this->params()->fromPost('auth_access_identity'))) === true
		)$this->view->credentialReset = true;
		elseif(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \LogicException('Reset credential process return expects string, "'.gettype($bReturn).'" given');
		}
		return $this->view;
	}

	/**
	 * Process reset credential request
	 * @throws \LogicException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function resetCredentialAction(){
		if(!($sPublicKey = $this->params('public_key')))throw new \LogicException('Public key param is missing');
		if(!($sEmailIdentity = $this->params('email_identity')))throw new \LogicException('Email identity param is missing');

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');
		$this->getServiceLocator()->get('AuthenticationService')->resetCredential($sPublicKey,$sEmailIdentity);
		return $this->view;
	}

	/**
	 * Logout user
	 * @throws \RuntimeException
	 */
	public function logoutAction(){
		if(!$this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()
		|| $this->getServiceLocator()->get('AuthenticationService')->logout())return (
			//Try to define redirect url
			empty($this->getServiceLocator()->get('Session')->redirect)
			&& ($sHttpReferer = $this->getRequest()->getServer('HTTP_REFERER'))
			&& is_array($aInfosUrl = parse_url($sHttpReferer))
			&& $this->getRequest()->getServer('HTTP_HOST') === $aInfosUrl['host']
		)?$this->redirect()->toUrl($sHttpReferer):$this->redirect()->toRoute('Home');
		else throw new \RuntimeException('Error occured during logout process');
	}
}