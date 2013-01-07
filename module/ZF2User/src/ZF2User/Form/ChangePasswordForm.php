<?php
namespace ZF2User\Form;
class ChangePasswordForm extends \Application\Form\AbstractForm{
	
	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('change_password',$aOptions);
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'required' => true,
				'class' => 'required maxLength:32'
			),
			'options' => array(
				'label' => 'current_password'
			)
		))
		->add(array(
			'name' => 'user_new_password',
			'attributes' => array(
				'type'  => 'password',
				'required' => true,
				'class' => 'required maxLength:32'
			),
			'options' => array(
				'label' => 'new_password'
			)
		))
		->add(array(
			'name' => 'user_confirm_password',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'required validate-match matchInput:\'user_new_password\' matchName:\''.$this->translate('password').'\'',
				'required' => true
			),
			'options' => array(
				'label' => 'confirm_password'
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
			'name' => 'user_password',
			'required' => true,
			'validators' => array(array('name'=> 'StringLength','options' => array('max'=>32)))
		))->add(array(
			'name' => 'user_new_password',
			'required' => true,
			'validators' => array(array('name'=> 'StringLength','options' => array('max'=>32)))
		))->add(array(
			'name' => 'user_confirm_password',
			'required' => true,
			'validators' => array(array('name'=> 'Identical','options' => array('token'=>'user_new_password')))
		)));
	}
}