<?php
namespace User\Form;
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
				'class' => 'required validate-email input-xlarge',
				'autofocus' => 'autofocus'
			),
			'options'=>array('twb'=>array('prepend'=>array('type'=>'icon','icon'=>'icon-envelope')))
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'placeholder' => 'password',
				'required' => true,
				'class' => 'required input-xlarge'
			),
			'options'=>array('twb'=>array('prepend'=>array('type'=>'icon','icon'=>'icon-lock')))
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'sign_in',
				'class' => 'btn-large btn-primary'
			),
			'options' => array('twb'=>array('formAction' => true))
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'user_email',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(array('name'=> 'EmailAddress'))
		)));
	}
}