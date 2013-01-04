<?php
namespace Messenger\Mail\InlineStyle;
class InlineStyleOptions extends \Zend\Stdlib\AbstractOptions{
	
	/**
	 * @var string
	 */
	protected $serverUrl;
	
	/**
	 * @param string $sServerUrl
	 * @throws \Exception
	 * @return InlineStyleOptions
	 */
	public function setServerUrl($sServerUrl){
		if(filter_var($sServerUrl,FILTER_VALIDATE_URL) === false)throw new \Exception('Server url expects valid url');
		$this->serverUrl = $sServerUrl;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getServerUrl(){
		return $this->serverUrl;
	}
}