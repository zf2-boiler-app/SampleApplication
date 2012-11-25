<?php
namespace Application\Mvc\Controller;
abstract class AbstractActionController extends \Neilime\AssetsBundle\Mvc\Controller\AbstractActionController{
	/**
	 * @var \Zend\View\Model\ViewModel
	 */
	protected $view;

	public function onDispatch(\Zend\Mvc\MvcEvent $oEvent){
		if(!$this->getRequest()->isXmlHttpRequest())$this->view = new \Zend\View\Model\ViewModel();
		return parent::onDispatch($oEvent);
	}
}