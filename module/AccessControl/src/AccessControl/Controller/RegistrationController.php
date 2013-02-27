<?php
namespace AccessControl\Controller;
class RegistrationController extends \Templating\Mvc\Controller\AbstractActionController{

	/**
	 * Show register form or process register attempt
	 * @return \Zend\View\Model\ViewModel | \Zend\Http\Response
	 */
	public function registerAction(){
		//If user is already logged in, redirect him
		if($this->getServiceLocator()->get('AccessControlAuthenticationService')->hasIdentity()){
			$sRedirectUrl = empty($this->getServiceLocator()->get('Session')->redirect)
			?$this->url()->fromRoute('Home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('register');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('RegisterForm');

		if($this->getRequest()->isPost()
			&& $this->view->form->setData($aRegisterData = $this->params()->fromPost())->isValid()
			&& $this->getServiceLocator()->get('RegistrationService')->register($aRegisterData)
		)$this->view->isValid = true;
		return $this->view;
	}

	/**
	 * Process ajax request to check email identity availability
	 * @throws \LogicException
	 * @return \Zend\View\Model\JsonModel
	 */
	public function checkEmailIdentityAvailabilityAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \LogicException('Only ajax requests are allowed for this action');
		if(!($sEmail = $this->params()->fromPost('email')))throw new \LogicException('Email param is missing');

		return $this->view->setVariable(
			'available',
			$this->getServiceLocator()->get('AccessControlService')->isEmailIdentityAvailable($sEmail)
		);
	}

	/**
	 * Process ajax request to check username identity availability
	 * @throws \LogicException
	 * @return \Zend\View\Model\JsonModel
	 */
	public function checkUsernameIdentityAvailabilityAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \LogicException('Only ajax requests are allowed for this action');
		if(!($sUserName = $this->params()->fromPost('username')))throw new \LogicException('Username param is missing');

		return $this->view->setVariable(
			'available',
			$this->getServiceLocator()->get('AccessControlService')->isUsernameIdentityAvailable($sUserName)
		);
	}

	/**
	 * Process email confirmation
	 * @throws \LogicException
	 * @throws \RuntimeException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function confirmEmailAction(){
		if(!($sPublicKey = $this->params('public_key')))throw new \LogicException('Public key param is missing');
		if(!($sEmailIdentity = $this->params('email_identity')))throw new \LogicException('Email identity param is missing');

		if(($bReturn = $this->getServiceLocator()->get('RegistrationService')->confirmEmail($sPublicKey,$sEmailIdentity)) !== true){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \LogicException('Confirm email process return expects string, "'.gettype($bReturn).'" given');
		}
		return $this->view;
	}
}