<?php
namespace Application\Translator;
use \Zend\I18n\Translator\Translator as OriginalTranslator;
class Translator extends OriginalTranslator{

	/**
	 * Retrieve available messages
	 * @param string $sLocale
	 * @param string $sTextDomain
	 * @return array
	 */
	public function getMessages($sLocale = null,$sTextDomain = 'default'){
		$sLocale = $sLocale?:$this->getLocale();
		if(!isset($this->messages[$sTextDomain][$sLocale]))$this->loadMessages($sTextDomain, $sLocale);
		if($this->messages[$sTextDomain][$sLocale] instanceof \Zend\I18n\Translator\TextDomain)return $this->messages[$sTextDomain][$sLocale]->getArrayCopy();
		if(null !== ($sFallbackLocale = $this->getFallbackLocale()) && $sLocale !== $sFallbackLocale)$this->loadMessages($sTextDomain, $sFallbackLocale);
		return $this->messages[$sTextDomain][$sFallbackLocale] instanceof \Zend\I18n\Translator\TextDomain?$this->messages[$sTextDomain][$sFallbackLocale]->getArrayCopy():array();
	}
}
