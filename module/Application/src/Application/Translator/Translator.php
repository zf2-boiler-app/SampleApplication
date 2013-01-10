<?php
namespace Application\Translator;
use \Zend\I18n\Translator\Translator as OriginalTranslator;
class Translator extends OriginalTranslator{
	/**
	 * Load messages for a given language and domain.
	 * @param  string $sTextDomain
	 * @param  string $sLocale
	 * @throws Exception\RuntimeException
	 * @return void
	 */
	protected function loadMessages($sTextDomain, $sLocale){
		if(!isset($this->messages[$sTextDomain]))$this->messages[$sTextDomain] = array();

		if(null !== ($oCache = $this->getCache())) {
			$sCacheId = 'Zend_I18n_Translator_Messages_' . md5($sTextDomain . $sLocale);
			if(null !== ($aResult = $oCache->getItem($sCacheId))){
				$this->messages[$sTextDomain][$sLocale] = $aResult;
				return;
			}
		}

		$bHasToCache = false;

		//Try to load from remote sources
		if(isset($this->remote[$sTextDomain]))foreach($this->remote[$sTextDomain] as $sLoaderType){
			$oLoader = $this->getPluginManager()->get($sLoaderType);
			if(!$oLoader instanceof \Zend\I18n\Translator\Loader\RemoteLoaderInterface)throw new \Exception(sprintf('Specified loader "%s" is not a remote loader',$sLoaderType));
			if(isset($this->messages[$sTextDomain][$sLocale]))$this->messages[$sTextDomain][$sLocale]->exchangeArray(array_merge(
				(array)$this->messages[$sTextDomain][$sLocale],
				(array)$oLoader->load($sLocale, $sTextDomain)
			));
			else $this->messages[$sTextDomain][$sLocale] = $oLoader->load($sLocale, $sTextDomain);
			$bHasToCache = true;
		}

		//Try to load from pattern
		if(isset($this->patterns[$sTextDomain])){
			foreach($this->patterns[$sTextDomain] as $aPatternInfos){
				$sFilename = $aPatternInfos['baseDir'] . '/' . sprintf($aPatternInfos['pattern'], $sLocale);
				if(is_file($sFilename)){
					$oLoader = $this->getPluginManager()->get($aPatternInfos['type']);
					if(!$oLoader instanceof \Zend\I18n\Translator\Loader\FileLoaderInterface)throw new \Exception(sprintf('Specified loader "%s" is not a file loader',$aPatternInfos['type']));
					if(isset($this->messages[$sTextDomain][$sLocale]))$this->messages[$sTextDomain][$sLocale]->exchangeArray(array_merge(
						(array)$this->messages[$sTextDomain][$sLocale],
						(array)$oLoader->load($sLocale, $sFilename)
					));
					else $this->messages[$sTextDomain][$sLocale] = $oLoader->load($sLocale, $sFilename);
					$bHasToCache = true;
				}
			}
		}

		//Try to load from concrete files
		foreach(array($sLocale, '*') as $sCurrentLocale){
			if(!isset($this->files[$sTextDomain][$sCurrentLocale]))continue;
			if(is_string(key($this->files[$sTextDomain][$sCurrentLocale])))$this->files[$sTextDomain][$sCurrentLocale] = array($this->files[$sTextDomain][$sCurrentLocale]);
			foreach($this->files[$sTextDomain][$sCurrentLocale] as $aFileInfos){
				$oLoader = $this->getPluginManager()->get($aFileInfos['type']);

				if(!$oLoader instanceof \Zend\I18n\Translator\Loader\FileLoaderInterface)throw new \Exception(sprintf('Specified loader "%s" is not a file loader',$aFileInfos['type']));
				if(isset($this->messages[$sTextDomain][$sLocale]))$this->messages[$sTextDomain][$sLocale]->exchangeArray(array_merge(
					(array)$this->messages[$sTextDomain][$sLocale],
					(array)$oLoader->load($sLocale, $aFileInfos['filename'])
				));
				else $this->messages[$sTextDomain][$sLocale] = $oLoader->load($sLocale, $aFileInfos['filename']);
				$bHasToCache = true;
			}
			unset($this->files[$sTextDomain][$sCurrentLocale]);
		}

		//Cache the loaded text domain
		if($bHasToCache && $oCache !== null)$oCache->setItem($sCacheId, $this->messages[$sTextDomain][$sLocale]);
	}

	/**
	 * Add a translation file.
	 * @param  string $type
	 * @param  string $filename
	 * @param  string $textDomain
	 * @param  string $locale
	 * @return Translator
	 */
	public function addTranslationFile($type,$filename,$textDomain = 'default',$locale = null){
		$locale = $locale ?: '*';
		if(!isset($this->files[$textDomain]))$this->files[$textDomain] = array();
		$this->files[$textDomain][$locale][] = array(
			'type' => $type,
			'filename' => $filename,
		);
		return $this;
	}

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
