<?php
namespace Blog\Controller;
class NewsController extends \Application\Mvc\Controller\AbstractActionController{
	public function indexAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('news');
		return $this->view;
	}

	public function createAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('create_a_news');
		return $this->view;
	}

	public function readAction(){
		//Define title
		return $this->view;
	}

	public function updateAction(){
		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('update_news');
		return $this->view;
	}

	public function deleteAction(){
		return $this->view;
	}
}