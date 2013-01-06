<?php
namespace ZF2User\Form;
class ResetPasswordForm extends \Zend\Form\Form{
	/**
	* Constructor
	*/
	public function __construct(){
		parent::__construct('reset_password');
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_email',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-email'
			),
			'options' => array(
				'label' => 'email'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'reset_password'
			),
			'options' => array(
				'primary' => true
			)
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'user_email',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(array('name'=> 'EmailAddress'))
		)));
	}
}