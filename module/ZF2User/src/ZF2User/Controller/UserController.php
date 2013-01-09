<?php
namespace ZF2User\Controller;
class UserController extends \Application\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('home')
			:$this->getServiceLocator()->get('Session')->redirect;

		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity()){
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
			($bReturn = $this->getServiceLocator()->get('UserService')->login(null,null,$this->params('service'))) === true
		) ||
		(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->login(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)) === true
		)){
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		if(isset($bReturn)){
			if(is_string($bReturn)){
				$this->view->error = $bReturn;
				$this->view->isPending = $bReturn === $this->getServiceLocator()->get('translator')->translate('user_state_pending');
			}
			else throw new \Exception('Authenticate process return invalid : '.gettype($bReturn));
		}
		return $this->view;
	}

	public function logoutAction(){
		if(!$this->getServiceLocator()->get('AuthService')->hasIdentity() || $this->getServiceLocator()->get('UserService')->logout())return $this->redirect()->toRoute('home');
		else throw new \Exception('Error occured during logout process');
	}

	public function registerAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity())return $this->redirect()->toUrl($sRedirectUrl);

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
			return $this->redirect()->toRoute('zf2user/login');
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

	public function accountAction(){
		//Check user is logged in
		if(($bReturn = $this->userMustBeLoggedIn()) !== true)return $bReturn;
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('account');
		return $this->view->setVariable('user', $this->getServiceLocator()->get('UserService')->getLoggedUser());
	}

	public function deleteaccountAction(){
		//Check user is logged in
		if(($bReturn = $this->userMustBeLoggedIn()) !== true)return $bReturn;

		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('delete_account');
		$this->getServiceLocator()->get('UserService')->deleteLoggedUser();
		return $this->view;
	}

	public function changepasswordAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \Exception('Only ajax requests are allowed for this action');

		//Check user is logged in
		if(($bReturn = $this->userMustBeLoggedIn()) !== true)return $bReturn;

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ChangePasswordForm');
		if(
			$this->getRequest()->isPost()
			&& $this->view->form->setData($this->params()->fromPost())->isValid()
			&& $this->getServiceLocator()->get('UserService')->changeUserLoggedPassword($this->params()->fromPost('user_new_password'))
		)$this->view->passwordChanged = true;
		return $this->view;
	}
}