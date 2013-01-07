<?php
namespace Application\Controller;
class IndexController extends \Application\Mvc\Controller\AbstractActionController{
    public function indexAction(){
    	//$this->getServiceLocator()->get('UserService')->logout();
    	return $this->view;
    }
}