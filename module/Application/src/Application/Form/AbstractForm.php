<?php
namespace Application\Form;
abstract class AbstractForm extends \Zend\Form\Form implements \Zend\I18n\Translator\TranslatorAwareInterface {
	use \Zend\I18n\Translator\TranslatorAwareTrait;

	/**
	 * Constructor
	 * @param string|null $sName
	 * @param array|null $aOptions
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct($sName,$aOptions);

		$this->setAttributes(array(
			//Default method
			'method' => 'post',
			//Add data behaviors
			'data-behavior' => 'FormValidator Meio.Mask'
		));
	}
}