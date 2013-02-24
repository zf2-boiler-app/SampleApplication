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

		$oHtmlAttrEscaper = new \Zend\View\Helper\EscapeHtmlAttr();

		$this->add(array(
			'name' => 'auth_access_email_identity',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-email',
				/*'class' => 'required validate-email emailIsAvailable',
				'onchange' => 'oController.checkEmailIdentityAvailability(document.id(this));',*/
				'autocomplete' => 'off',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'email'
			)
		))->add(array(
			'name' => 'auth_access_username_identity',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-nospace maxLength:255 usernameIsAvailable',
				'onchange' => 'oController.checkUsernameIdentityAvailability(document.id(this));',
				'autocomplete' => 'off',
			),
			'options' => array(
				'label' => 'username'
			)
		))
		->add(array(
			'name' => 'auth_access_credential',
			'attributes' => array(
				'id' => 'auth_access_credential',
				'type' => 'password',
				'required' => true,
				'class' => 'required maxLength:32',
				'autocomplete' => 'off',
				'data-behavior' => ' Form.PasswordStrength'
			),
			'options' => array(
				'label' => 'password'
			)
		))
		->add(array(
			'name' => 'auth_access_credential_confirm',
			'attributes' => array(
				'type' => 'password',
				'class' => 'required validate-match matchInput:\'auth_access_credential\' matchName:\''.$oHtmlAttrEscaper('"'.$this->getTranslator()->translate('password').'"').'\'',
				'required' => true,
				'autocomplete' => 'off'
			),
			'options' => array(
				'label' => 'confirm_password'
			)
		))
		->add(array(
			'name' => 'user_captcha',
			'type' => 'Zend\Form\Element\Captcha',
			'attributes' => array(
				'required' => true,
				'placeholder' => sprintf($this->getTranslator()->translate('enter_the_x_characters'),$oCaptchaImage->getWordlen()),
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
			'type' => 'submit',
				'value' => 'register',
				'class' => 'btn-large btn-primary'
			),
			'options' => array(
            	'ignore' => true,
				'twb' => array('formAction' => true)
			)
		));
		return parent::prepare();
	}
}