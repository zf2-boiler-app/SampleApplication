<?php
namespace ZF2User\Form;
class LoginForm extends \Application\Form\AbstractForm{
	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('login',$aOptions);
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_email',
			'attributes' => array(
				'placeholder' => 'email',
				'required' => true,
				'class' => 'required validate-email'
			)
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'placeholder' => 'password',
				'required' => true,
				'class' => 'required'
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