<?php
namespace User\Controller;
class UserAccountController extends \Templating\Mvc\Controller\AbstractActionController{

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
		$this->getServiceLocator()->get('UserAccountService')->deleteLoggedUser();
		return $this->view;
	}

	public function changeavatarAction(){
		if($this->getRequest()->isPost())$this->view->setTerminal(true);
		elseif(!$this->getRequest()->isXmlHttpRequest())throw new \Exception('Only ajax requests are allowed for this action');

		//Check user is logged in
		if(($bReturn = $this->userMustBeLoggedIn()) !== true)return $bReturn;

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ChangeAvatarForm');
		if(
			$this->getRequest()->isPost()
			&& $this->view->form->setData($this->params()->fromFiles())->isValid()
			&& $this->getServiceLocator()->get('UserAccountService')->changeUserLoggedAvatar($this->params()->fromFiles('user_new_avatar'))
		)$this->view->user = $this->getServiceLocator()->get('UserService')->getLoggedUser();
		return $this->view;
	}

	public function changeemailAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \Exception('Only ajax requests are allowed for this action');

		//Check user is logged in
		if(($bReturn = $this->userMustBeLoggedIn()) !== true)return $bReturn;

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('ChangeEmailForm');
		if(
			$this->getRequest()->isPost()
			&& $this->view->form->setData($this->params()->fromPost())->isValid()
			&& $this->getServiceLocator()->get('UserAccountService')->changeUserLoggedEmail($this->params()->fromPost('user_new_email'))
		)$this->view->emailChanged = true;
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
			&& $this->getServiceLocator()->get('UserAccountService')->changeUserLoggedPassword($this->params()->fromPost('user_new_password'))
		)$this->view->passwordChanged = true;
		return $this->view;
	}
}