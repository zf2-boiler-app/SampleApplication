<?php
namespace ZF2User\Form;
class Login extends \Zend\Form\Form{
	/**
	* Constructor
	*/
	public function __construct(){
		parent::__construct('login');
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_email',
			'attributes' => array(
				'type' => 'text',
				'placeholder'   => 'email',
			),
			'options' => array(
				'prependIcon' => 'icon-email'
			)
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'placeholder'   => 'password',
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
		->getInputFilter()->add(array(
			'name' => 'user_email',
			'required' => true,
			'validators' => array(
				array('name'=> 'EmailAddress')
			)
		));
	}
}