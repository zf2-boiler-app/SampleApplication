<?php
namespace AccessControl\Form;
class RegisterForm extends \Application\Form\AbstractForm{

	/**
	 * @see \Zend\Form\Form::prepare()
	 */
	public function prepare(){
		if($this->isPrepared)return $this;
		$oCaptchaImage = new \Zend\Captcha\Image(array(
			'font' =>  './data/fonts/ARIAL.ttf',
			'fsize' => 30,
			'width' => 220,
			'height' => 70,
			'dotNoiseLevel' => 40,
			'lineNoiseLevel' => 3,
			'wordlen' => 6
		));

		$this->add(array(
			'name' => 'user_email',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-email emailIsAvailable',
				'onchange' => 'oController.checkUserEmailAvailability(document.id(this));',
				'autocomplete' => 'off',
				'autofocus' => 'autofocus'
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
				'class' => 'required maxLength:32',
				'autocomplete' => 'off'
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
				'required' => true,
				'autocomplete' => 'off'
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
				'autocomplete' => 'off'
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
				'value' => 'register',
				'class' => 'btn-large btn-primary'
			),
			'options' => array('twb' => array('formAction' => true))
		));
		return parent::prepare();
	}
}