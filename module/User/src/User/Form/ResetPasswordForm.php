<?php
namespace User\Form;
class ResetPasswordForm extends \Application\Form\AbstractForm{
	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('reset_password',$aOptions);
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