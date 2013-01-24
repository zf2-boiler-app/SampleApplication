<?php
namespace User\Form;
class ChangeEmailForm extends \Application\Form\AbstractForm{

	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('change_password',$aOptions);
		if(!isset($this->options['checkUserEmailAvailability']))throw new \Exception('Option "checkUserEmailAvailability" is undefined');
		if(!isset($this->options['userEmail']))throw new \Exception('Option "userEmail" is undefined');

		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_new_email',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-email emailIsAvailable',
				'onchange' => 'oController.checkUserEmailAvailability(document.id(this));',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'email'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'change_email'
			),
			'options' => array(
				'primary' => true
			)
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'user_new_email',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(
				array('name'=> 'EmailAddress'),
				array('name'=> 'User\Validator\EmailAddressAvailabilityValidator','options' => array(
					'checkUserEmailAvailability' => $this->options['checkUserEmailAvailability'],
					'currentEmail' => $this->options['userEmail']
				))
			)
		)));
	}
}