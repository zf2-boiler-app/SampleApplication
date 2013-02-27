<?php
namespace AccessControl\Form;
class ResetCredentialForm extends \Application\Form\AbstractForm{
	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct($sName,$aOptions);
		$this->add(array(
			'name' => 'auth_access_identity',
			'attributes' => array(
				'required' => true,
				'class' => 'required',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'email_or_username'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'reset_password',
				'class' => 'btn-large btn-primary'
			),
			'options' => array(
				'ignore' => true,
				'twb' => array('formAction' => true)
			)
		));
	}
}