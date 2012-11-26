<?php
namespace ZF2User\Controller;
class UserController extends \Application\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('sign_in');
		$this->view->form = $this->getServiceLocator()->get('UserService')->getLoginForm();
		if(
			$this->getRequest()->isPost() && $this->view->form->setData($this->params()->fromPost())->isValid() &&
			($bReturn = $this->getServiceLocator()->get('UserService')->login(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)) === true
		)return $this->redirect($this->fromRoute('home'));
		return $this->view;
	}
}
