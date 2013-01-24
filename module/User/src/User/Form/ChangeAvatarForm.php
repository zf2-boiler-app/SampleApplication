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
			'attributes' => array(
				'required' => true,
				'class' => 'required',
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
				'value' => 'change_avatar'
			),
			'options' => array(
				'primary' => true
			)
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'user_new_avatar',
			'type' => 'Zend\InputFilter\FileInput',
    		'required' => true,
    		'validators' => array(
    			array(
    				'name' => 'File\Extension',
    				'options' => array('extension' => 'png')
    			)
    		)
		)));
	}
}