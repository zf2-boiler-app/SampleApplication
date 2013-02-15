<?php
namespace Blog\Form;
class PostForm extends \Application\Form\AbstractForm{
	/**
	 * Constructor
	 * @param string $sName
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct('post',$aOptions);
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'post_id',
			'type' => 'hidden'
		))
		->add(array(
			'name' => 'post_title',
			'attributes' => array(
				'required' => true,
				'class' => 'required input-block-level',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'title'
			)
		))
		->add(array(
			'name' => 'post_content',
			'type' => 'textarea',
			'attributes' => array(
				'required' => true,
				'class' => 'required input-block-level'
			),
			'options' => array(
				'label' => 'content'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'create',
				'class' => 'btn-primary btn-large'
			),
			'options' => array('twb'=>array('formAction' => true))
		))->setInputFilter(new \Blog\InputFilter\PostInputFilter());
	}
}