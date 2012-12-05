<?php
namespace ZF2User\Controller;
class UserController extends \Application\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		$sRedirectUrl = $this->params('redirect')?:$this->url()->fromRoute('home');
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity())$this->redirect()->toUrl($sRedirectUrl);

		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('sign_in');
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
		))return $this->redirect()->toUrl($sRedirectUrl);

		if(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \Exception('Authenticate process return invalid : '.gettype($bReturn));
		}
		return $this->view;
	}

	public function logoutAction(){
		if(!$this->getServiceLocator()->get('AuthService')->hasIdentity() || $this->getServiceLocator()->get('UserService')->logout())return $this->redirect()->toRoute('home');
		else throw new \Exception('Error occured during logout process');
	}

	public function registerAction(){
		$sRedirectUrl = $this->params('redirect')?:$this->url()->fromRoute('home');
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity())return $this->redirect()->toUrl($sRedirectUrl);

		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('register');
		$this->view->form = $this->getServiceLocator()->get('RegisterForm');

		if($this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->register(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)) === true
		)return $this->redirect($sRedirectUrl);
		elseif(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \Exception('Register process return invalid : '.gettype($bReturn));
		}
		return $this->view;
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
}
