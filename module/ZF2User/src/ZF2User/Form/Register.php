<?php
namespace ZF2User\Form;
class Register extends \Zend\Form\Form{
	/**
	* Constructor
	*/
	public function __construct(){
		parent::__construct('login');
		$oInputFilter = new \Zend\InputFilter\InputFilter();

		$oCaptchaImage = new \Zend\Captcha\Image(array(
			'font' =>  './data/fonts/ARIAL.ttf',
			'fsize' => 30,
			'width' => 220,
			'height' => 70,
			'dotNoiseLevel' => 40,
			'lineNoiseLevel' => 3,
			'wordlen' => 6
		));

		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_email',
			'type' => 'Zend\Form\Element\Email',
			'attributes' => array(
				'required' => true
			),
			'options' => array(
				'label' => 'email'
			)
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
			'type'  => 'password',
				'required' => true
			),
			'options' => array(
				'label' => 'password'
			)
		))
		->add(array(
			'name' => 'user_confirm_password',
			'attributes' => array(
			'type'  => 'password',
				'required' => true
			),
			'options' => array(
				'label' => 'confirm_password'
			)
		))
		->add(array(
			'name' => 'user_captcha',
			'type'  => 'Zend\Form\Element\Captcha',
			'attributes' => array(
				'required' => true
			),
			'options' => array(
				'label' => 'im_not_a_robot',
				'captcha' => $oCaptchaImage->setImgDir('./public/assets/captcha')->setImgUrl('/assets/captcha/')
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
			'type'  => 'submit',
				'value' => 'register'
			),
			'options' => array(
				'primary' => true
			)
		))
		->setInputFilter($oInputFilter->add(
			array(
				'name' => 'user_email',
				'required' => true,
				'filters' => array(array('name' => 'StringTrim')),
				'validators' => array(array('name'=> 'EmailAddress'))
			)
		));
	}
}