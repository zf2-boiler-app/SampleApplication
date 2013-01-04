<?php
namespace Messenger\Mail\InlineStyle;
class InlineStyleService{
	/**
	 * @var \Messenger\Mail\InlineStyle\InlineStyleOptions
	 */
	protected $options;
	
	/**
	 * @var \InlineStyle\InlineStyle
	 */
	protected $inlineStyle;
	
	public function __construct(\Messenger\Mail\InlineStyle\InlineStyleOptions $oOptions){
		$this->options = $oOptions;
	}
	
	/**
	 * Set configuration parameters for InlineStyle
	 * @param  array|Traversable $aOptions
	 * @return \Messenger\Mail\InlineStyle
	 * @throws \Exception
	 */
	public function setOptions($aOptions = array()){
		if($aOptions instanceof Traversable)$aOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($aOptions);
		if(!is_array($aOptions))throw new \Exception('Config parameter is not valid');
	
		/** Config Key Normalization */
		foreach($aOptions as $sKey => $sValue){
			$this->config[str_replace(array('-', '_', ' ', '.'), '', strtolower($sKey))] = $sValue; // replace w/ normalized
		}
		return $this;
	}
	
	/**
	 * @param string $sHtml
	 * @throws \Exception
	 * @return string
	 */
	public function processHtml($sHtml){
		if(!is_string($sHtml))throw new \Exception('Html expects string');
		return $this->getInlineStyle($sHtml)->getHTML();
	}

	/**
	 * @param string $sFileName
	 * @throws \Exception
	 * @return string
	 */
	public function processFile($sFileName){
		if(!is_string($sFileName))throw new \Exception('File name expects string');
		if(!file_exists($sFileName) || ($sHtml = file_get_contents($sFileName)) === false)throw new \Exception('File "'.$sFileName.'" not found or can\'t be read');
		return $this->processHtml($sHtml);
	}
	
	/**
	 * @param string $sHtml
	 * @throws \Exception
	 * @return \InlineStyle\InlineStyle
	 */
	private function getInlineStyle($sHtml = null){
		if($this->inlineStyle instanceof \InlineStyle\InlineStyle)$this->inlineStyle->loadHTML($sHtml);
		elseif(class_exists('\InlineStyle\InlineStyle'))$this->inlineStyle = new \InlineStyle\InlineStyle($sHtml);
		else throw new \Exception('\InlineStyle\InlineStyle class is undefined');
		return $this->inlineStyle->applyStylesheet($this->inlineStyle->extractStylesheets(null,$this->options->getServerUrl()));
	}
	
	/**
	 * Extract styles from css link files
	 * @param string $sHtml
	 * @throws \Exception
	 * @return string
	 */
	private function retrieveStyles($sHtml){
		if(!is_string($sHtml))throw new \Exception('Html expects string');
		$sStyles = '';
		if(preg_match_all('/<link[^href]?href=["\'](.+?)["\']/', $sHtml,$aMatches))foreach($aMatches[1] as $sUrl){
			$sUrl = str_ireplace('/', DIRECTORY_SEPARATOR, parse_url($sUrl, PHP_URL_PATH));
			if((
				($sFile = realpath($sUrl)) !== false
				|| ($this->options->getBasePath() && ($sFile = realpath($this->options->getBasePath().DIRECTORY_SEPARATOR.$sUrl)) !== false)
				|| ($sFile = realpath(getcwd().DIRECTORY_SEPARATOR.$sUrl)) !== false
			) && is_readable($sFile) && is_file($sFile) && ($sStyle = file_get_contents($sFile)) !== false)$sStyles .= PHP_EOL.$sStyle;
		}
		return $sStyles;
	}
}