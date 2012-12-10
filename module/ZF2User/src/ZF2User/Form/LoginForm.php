<?php
namespace ZF2User\Form;
class LoginForm extends \Zend\Form\Form{
	/**
	* Constructor
	*/
	public function __construct(){
		parent::__construct('login');
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_email',
			'attributes' => array(
				'placeholder' => 'email',
				'required' => true
			)
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'placeholder' => 'password',
				'required' => true
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'sign_in'
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