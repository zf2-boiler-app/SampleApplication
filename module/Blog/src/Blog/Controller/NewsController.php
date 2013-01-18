<?php
namespace Blog\Controller;
class NewsController extends \Application\Mvc\Controller\AbstractActionController{
	public function indexAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('news');
		return $this->view;
	}
}