<?php
namespace ZF2User\Controller;
class UserController extends \Application\Mvc\Controller\AbstractActionController{
	public function loginAction(){
		//$this->view->form = $this->getServiceLocator()->get('UserService')->getLoginForm();
		return $this->view;
	}
}
