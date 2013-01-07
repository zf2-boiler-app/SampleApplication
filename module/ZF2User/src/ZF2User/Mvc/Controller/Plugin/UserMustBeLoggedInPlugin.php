<?php
namespace ZF2User\Mvc\Controller\Plugin;
class UserMustBeLoggedInPlugin extends \Zend\Mvc\Controller\Plugin\AbstractPlugin{
    public function __invoke($sRedirectUrl = null){
    	/* @var $oController \Zend\Mvc\Controller\AbstractActionController */
    	$oController = $this->getController();
    	$oServiceLocator = $oController->getServiceLocator();
    	if(!$oServiceLocator->get('AuthService')->hasIdentity()){
			$oController->flashMessenger()->addMessage($oServiceLocator->get('translator')->translate('user_must_be_logged_in_to_access_this_page'));
			if($sRedirectUrl !== false)$oServiceLocator->get('Session')->redirect = $sRedirectUrl?:$oController->getRequest()->getUri()->normalize();
			return $oController->redirect()->toRoute('zf2user/login');
		}
		return true;
    }
}