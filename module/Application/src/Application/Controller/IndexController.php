<?php
namespace Application\Controller;
class IndexController extends \BoilerAppDisplay\Mvc\Controller\AbstractActionController{
    public function indexAction(){
    	//Define title
    	$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('home');
    	return $this->view;
    }

    public function privacyAction(){
    	//Define title
    	$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('privacy');
    	return $this->view;
    }

    public function termsAction(){
    	//Define title
    	$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('terms');
    	return $this->view;
    }
}