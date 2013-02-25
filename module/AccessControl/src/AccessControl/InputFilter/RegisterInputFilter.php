<?php
namespace AccessControl\InputFilter;
class RegisterInputFilter extends \Zend\InputFilter\InputFilter{
	/**
	 * Constructor
	 * @param \AccessControl\Repository\AuthAccessRepository $oAuthAccessRepository
	 * @param \Zend\I18n\Translator\Translator $oTranslator
	 */
    public function __construct(\AccessControl\Repository\AuthAccessRepository $oAuthAccessRepository,\Zend\I18n\Translator\Translator $oTranslator){
    	$this->add(array(
			'name' => 'auth_access_email_identity',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(
				array('name'=> 'EmailAddress','break_chain_on_failure' => true),
				array(
					'name'=> 'AccessControl\Validator\IdentityAvailabilityValidator',
					'options' => array(
						'identityName' => $oTranslator->translate('the_email'),
						'checkAvailabilityCallback' => array($oAuthAccessRepository, 'isIdentityEmailAvailable')
					)
				),
			)
		))
		->add(array(
			'name' => 'auth_access_username_identity',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(
				array('name'=> 'Application\Validator\NoSpaces','break_chain_on_failure' => true),
				array(
					'name'=> 'stringLength',
					'options' => array('max' => 255),
					'break_chain_on_failure' => true
				),
				array(
					'name'=> 'AccessControl\Validator\IdentityAvailabilityValidator',
					'options' => array(
						'identityName' => $oTranslator->translate('the_username'),
						'checkAvailabilityCallback' => array($oAuthAccessRepository, 'isIdentityUsernameAvailable')
					)
				)
			)
		))->add(array(
			'name' => 'auth_access_credential',
			'required' => true
		))->add(array(
			'name' => 'auth_access_credential_confirm',
			'required' => true,
			'validators' => array(array('name'=> 'Identical','options' => array('token'=>'auth_access_credential')))
		));
    }
}