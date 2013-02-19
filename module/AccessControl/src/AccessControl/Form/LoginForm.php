<?php
namespace AccessControl\Form;
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
			'name' => 'identity',
			'attributes' => array(
				'placeholder' => 'email_or_user_name',
				'required' => true,
				'class' => 'required input-xlarge',
				'autofocus' => 'autofocus'
			),
			'options'=>array('twb'=>array('prepend'=>array('type'=>'icon','icon'=>'icon-user')))
		))
		->add(array(
			'name' => 'credential',
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
			'name' => 'identity',
			'required' => true
		))->add(array(
			'name' => 'credential',
			'required' => true
		)));
	}
}