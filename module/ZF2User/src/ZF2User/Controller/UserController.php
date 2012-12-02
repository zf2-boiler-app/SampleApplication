<?php
namespace ZF2User\Controller;
class UserController extends \Application\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		$sRedirectUrl = $this->params('redirect')?:$this->url()->fromRoute('home');
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity())$this->redirect($sRedirectUrl);

		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('sign_in');
		$this->view->form = $this->getServiceLocator()->get('LoginForm');

		if($this->params('service'))$this->getServiceLocator()->get('UserService')->login(null,null,$this->params('service'));
		elseif(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->login(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)) === true
		)return $this->redirect($sRedirectUrl);
		elseif(isset($bReturn)){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \Exception('Authenticate process return invalid : '.gettype($bReturn));
		}
		return $this->view;
	}

	public function registerAction(){
		$sRedirectUrl = $this->params('redirect')?:$this->url()->fromRoute('home');
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AuthService')->hasIdentity())$this->redirect($sRedirectUrl);

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
		\Hybrid_Endpoint::process();
	}
}
