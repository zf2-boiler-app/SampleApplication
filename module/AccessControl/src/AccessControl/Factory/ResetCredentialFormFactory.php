<?php
namespace AccessControl\Factory;
class ResetCredentialFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \AccessControl\Form\ResetCredentialForm('reset-credential');
		return $oForm->setInputFilter(new \AccessControl\InputFilter\ResetCredentialInputFilter())->prepare();
    }
}