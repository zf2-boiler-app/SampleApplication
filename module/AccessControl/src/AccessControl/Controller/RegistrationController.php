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
			?$this->url()->fromRoute('home')
			:$this->getServiceLocator()->get('Session')->redirect;
			unset($this->getServiceLocator()->get('Session')->redirect);
			return $this->redirect()->toUrl($sRedirectUrl);
		}

		//Define title
		$this->layout()->title = $this->getServiceLocator()->get('Translator')->translate('register');

		//Assign form
		$this->view->form = $this->getServiceLocator()->get('RegisterForm');

		if($this->getRequest()->isPost()
			&& $this->view->form->setData($this->params()->fromPost())->isValid()
			&& $this->getServiceLocator()->get('AccessControlService')->register(
				$this->params()->fromPost('user_email'),
				$this->params()->fromPost('user_password')
			)
		)$this->view->isValid = true;
		return $this->view;
	}

	/**
	 * Process email confirmation
	 * @throws \LogicException
	 * @throws \RuntimeException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function confirmemailAction(){
		if(!($sRegistrationKey = $this->params('registration_key')))throw new \LogicException('Registration key param is missing');
		if(($bReturn = $this->getServiceLocator()->get('AccessControlService')->confirmEmail($sRegistrationKey)) !== true){
			if(is_string($bReturn))$this->view->error = $bReturn;
			else throw new \LogicException('Confirm email process return expects string, "'.gettype($bReturn).'" given');
		}
		return $this->view;
	}

	/**
	 * Process ajax request to resend email confirmation
	 * @throws \LogicException
	 * @return \Zend\View\Model\JsonModel
	 */
	public function resendconfirmationemailAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \LogicException('Only ajax requests are allowed for this action');
		if(!($sEmail = $this->params()->fromPost('email')))throw new \LogicException('Email param is missing');
		$this->getServiceLocator()->get('AccessControlService')->resendConfirmationEmail($sEmail);
		return $this->view;
	}

	/**
	 * Process ajax request to check user's email availability
	 * @throws \LogicException
	 * @return \Zend\View\Model\JsonModel
	 */
	public function checkuseremailavailabilityAction(){
		if(!$this->getRequest()->isXmlHttpRequest())throw new \LogicException('Only ajax requests are allowed for this action');
		if(!($sEmail = $this->params()->fromPost('email')))throw new \LogicException('Email param is missing');

		return $this->view->setVariable(
			'available',
			$this->getServiceLocator()->get('AccessControlService')->isUserEmailAvailable($sEmail)
		);
	}
}