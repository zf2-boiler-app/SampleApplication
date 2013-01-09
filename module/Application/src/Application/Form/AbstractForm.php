<?php
namespace Application\Form;
abstract class AbstractForm extends \Zend\Form\Form implements \Zend\I18n\Translator\TranslatorAwareInterface {
	/**
	 * @var \Zend\I18n\Translator\Translator
	 */
	private $translator;

	/**
	 * @var string
	 */
	private $textDomain;

	/**
	 * @var boolean
	 */
	private $translatorEnabled;

	/**
	 * Constructor
	 * @param string|null $sName
	 * @param array|null $aOptions
	 */
	public function __construct($sName = null,$aOptions = null){
		parent::__construct($sName,$aOptions);
		if(isset($this->options['translator']))$this->setTranslator($aOptions['translator']);

		//Add data behaviors
		$this->setAttribute('data-behavior', 'FormValidator Meio.Mask');
	}


	/**
	 * @param string $sTextDomain
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslatorTextDomain()
	 * @return \Application\Form\AbstractForm
	 */
	public function setTranslatorTextDomain($sTextDomain = 'default'){
		$this->textDomain = $sTextDomain;
		return $this;
	}

	/**
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::isTranslatorEnabled()
	 * @return boolean
	 */
	public function isTranslatorEnabled(){
		return $this->translatorEnabled;
	}

	/**
	 * @param boolean $bEnabled
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslatorEnabled()
	 * @return \Application\Form\AbstractForm
	 */
	public function setTranslatorEnabled($bEnabled = true){
		$this->translatorEnabled = !!$bEnabled;
		return $this;
	}

	/**
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::getTranslator()
	 * @throws \Exception
	 * @return \Zend\I18n\Translator\Translator
	 */
	public function getTranslator(){
		if($this->hasTranslator())return $this->translator;
		else throw new \Exception('Translator is disabled or undefined');

	}

	/**
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::getTranslatorTextDomain()
	 * @return string
	 */
	public function getTranslatorTextDomain(){
		return $this->textDomain;
	}

	/**
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::hasTranslator()
	 * @return boolean
	 */
	public function hasTranslator(){
		return $this->translatorEnabled && $this->translator instanceof \Zend\I18n\Translator\Translator;
	}

	/**
	 * @param \Zend\I18n\Translator\Translator $oTranslator
	 * @param string $sTextDomain
	 * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslator()
	 * @return \Application\Form\AbstractForm
	 */
	public function setTranslator(\Zend\I18n\Translator\Translator $oTranslator = null, $sTextDomain = null){
		$this->translator = $oTranslator;
		return $this->setTranslatorEnabled()->setTranslatorTextDomain($sTextDomain);
	}

	/**
	 * Translate message
	 * @param string $sMessage
	 * @param string $sTextDomain
	 * @param string $sLocale
	 * @return string
	 */
	protected function translate($sMessage, $sTextDomain = 'default', $sLocale = null){
		return $this->getTranslator()->translate($sMessage,$sTextDomain,$sLocale);
	}
}