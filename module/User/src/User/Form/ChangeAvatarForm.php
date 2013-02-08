<?php
namespace User\Form;
class ChangeAvatarForm extends \Application\Form\AbstractForm{

	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('change_avatar',$aOptions);

		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'user_new_avatar',
			'type' => 'Zend\Form\Element\File',
			'attributes' => array(
				'required' => true,
				'class' => 'required validate-file-extension:\'png,jpg,gif,jpeg\'',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'avatar'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'change_avatar',
				'class' => 'btn-large btn-primary'
			),
			'options' => array('twb'=>array('formAction' => true))
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'user_new_avatar',
			'required' => true
		)));
	}
}