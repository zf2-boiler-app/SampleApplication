<?php
namespace User\Controller;
class UserController extends \Templating\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('UserAuthenticationService')->hasIdentity()){
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

		if($this->flashMessenger()->hasCurrentMessages()){
			$bReturn = current($this->flashMessenger()->getCurrentMessages());
			$this->flashMessenger()->clearCurrentMessages();
		}
		elseif((
			$this->params('service') &&
			($bReturn = $this->getServiceLocator()->get('UserService')->login(\User\Service\UserService::HYBRID_AUTH_AUTHENTICATION,$this->params('service'))) === true
		) ||
		(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->login(
				\User\Service\UserService::LOCAL_AUTHENTICATION,
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
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
			else throw new \Exception('Authenticate process failed return type expects string, "'.gettype($bReturn).'" given');
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

	public function logoutAction(){
		if(!$this->getServiceLocator()->get('UserAuthenticationService')->hasIdentity()
		|| $this->getServiceLocator()->get('UserService')->logout())return (
			//Try to define redirect url
			empty($this->getServiceLocator()->get('Session')->redirect)
			&& ($sHttpReferer = $this->getRequest()->getServer('HTTP_REFERER'))
			&& is_array($aInfosUrl = parse_url($sHttpReferer))
			&& $this->getRequest()->getServer('HTTP_HOST') === $aInfosUrl['host']
		)?$this->redirect()->toUrl($sHttpReferer):$this->redirect()->toRoute('home');
		else throw new \Exception('Error occured during logout process');
	}

	public function registerAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('UserAuthenticationService')->hasIdentity()){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('register');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('RegisterForm');

		if($this->getRequest()->isPost()
			&& $this->view->form->setData($this->params()->fromPost())->isValid()
			&& $this->getServiceLocator()->get('UserService')->register(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)
		)$this->view->isValid = true;
		return $this->view;
	}

	public function confirmemailAction(){
		if(!($sRegistrationKey = $this->params('registration_key')))throw new \Exception('Registration key param is missing');
		if(($bReturn = $this->getServiceLocator()->get('UserService')->confirmEmail($sRegistrationKey)) !== true){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \Exception('Confirm email process return invalid : '.gettype($bReturn));
		}
		return $this->view;
	}

	public function resendconfirmationemailAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \Exception('Only ajax requests are allowed for this action');
		if(!($sEmail = $this->params()->fromPost('email')))throw new \Exception('Email param is missing');
		$this->getServiceLocator()->get('UserService')->resendConfirmationEmail($sEmail);
		return $this->view;
	}

	public function checkuseremailavailabilityAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \Exception('Only ajax requests are allowed for this action');
		if(!($sEmail = $this->params()->fromPost('email')))throw new \Exception('Email param is missing');

		return $this->view->setVariable(
			'available',
			$this->getServiceLocator()->get('UserService')->isUserEmailAvailable($sEmail)
		);
	}

	public function hybridauthAction(){
		if(($sProvider = $this->params()->fromQuery('hauth_done')) && $this->params()->fromQuery('error') === 'access_denied'){
			$this->flashMessenger()->addMessage(sprintf(
				$this->getServiceLocator()->get('translator')->translate('provider_authentification_canceled'),
				$sProvider
			));
			return $this->redirect()->toRoute('User/login');
		}
		\Hybrid_Endpoint::process();
	}

	public function forgottenpasswordAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ResetPasswordForm');

		if($this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->sendConfirmationResetPassword($this->params()->fromPost('user_email'))) === true
		)$this->view->passwordReset = true;
		elseif(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \Exception('Reset password process return invalid : '.gettype($bReturn));
		}
		return $this->view;
	}

	public function resetpasswordAction(){
		if(!($sResetKey = $this->params('reset_key')))throw new \Exception('Reset key param is missing');
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('reset_password');
		$this->getServiceLocator()->get('UserService')->resetPassword($sResetKey);
		return $this->view;
	}
}