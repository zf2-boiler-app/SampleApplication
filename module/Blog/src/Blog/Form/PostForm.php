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
		$oInputFilter = new \Zend\InputFilter\InputFilter();
		$this->setAttribute('method', 'post')
		->add(array(
			'name' => 'post_title',
			'attributes' => array(
				'required' => true,
				'class' => 'required',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'title'
			)
		))
		->add(array(
			'name' => 'post_category',
			'type' => 'select',
			'attributes' => array(
				'required' => true,
				'class' => 'required'
			),
			'options' => array(
				'label' => 'category',
				'value_options' => array($aOptions['post_categories'])
			)
		))
		->add(array(
			'name' => 'post_date',
			'type' => 'Zend\Form\Element\DateTimeLocal',
			'attributes' => array(
				'required' => true,
				'class' => 'required'
			),
			'options' => array(
				'label' => 'date_of_publication',
				'value' => time()
			)
		))
		->add(array(
			'name' => 'post_content',
			'type' => 'CKEditorBundle\Form\Element\CKEditor',
			'attributes' => array(
				'required' => true,
				'class' => 'required'
			),
			'options' => array(
				'label' => 'content'
			)
		))
		->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'create'
			),
			'options' => array(
				'primary' => true
			)
		))
		->setInputFilter($oInputFilter->add(array(
			'name' => 'post_category',
			'required' => true
		)));
	}
}