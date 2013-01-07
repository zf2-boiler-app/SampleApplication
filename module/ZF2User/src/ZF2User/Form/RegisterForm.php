<?php
namespace ZF2User\Form;
class RegisterForm extends \Application\Form\AbstractForm{

	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('register',$aOptions);
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		if(!isset($this->options['checkUserEmailAvailability']))throw new \Exception('Option "checkUserEmailAvailability" is undefined');
		
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
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-email emailIsAvailable',
				'onchange' => 'oController.checkUserEmailAvailability(document.id(this));'
			),
			'options' => array(
				'label' => 'email'
			)
		))
		->add(array(
			'name' => 'user_password',
			'attributes' => array(
				'type'  => 'password',
				'required' => true,
				'class' => 'required maxLength:32'
			),
			'options' => array(
				'label' => 'password'
			)
		))
		->add(array(
			'name' => 'user_confirm_password',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'required validate-match matchInput:\'user_password\' matchName:\''.$this->translate('password').'\'',
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
				'required' => true,
				'placeholder' => sprintf($this->translate('enter_the_x_characters'),$oCaptchaImage->getWordlen()),
			),
			'options' => array(
				'label' => 'im_not_a_robot',
				'captcha' => $oCaptchaImage->setImgDir('./public/assets/captcha')->setImgUrl('/assets/captcha/'),
				'class' => 'required'
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
		->setInputFilter($oInputFilter->add(array(
				'name' => 'user_email',
				'required' => true,
				'filters' => array(array('name' => 'StringTrim')),
				'validators' => array(
					array('name'=> 'EmailAddress'),
					array('name'=> 'ZF2User\Validator\EmailAddressAvailabilityValidator','options' => array('checkUserEmailAvailability'=>$this->options['checkUserEmailAvailability']))
				)
			))->add(array(
				'name' => 'user_password',
				'required' => true,
				'validators' => array(array('name'=> 'StringLength','options' => array('max'=>32)))
			))->add(array(
				'name' => 'user_confirm_password',
				'required' => true,
				'validators' => array(array('name'=> 'Identical','options' => array('token'=>'user_password')))
			))
		);
	}
}