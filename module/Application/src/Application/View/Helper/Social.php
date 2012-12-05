<?php
namespace Application\View\Helper;
class Social extends \Zend\View\Helper\AbstractHelper{
	const FACEBOOK = 'facebook';
	const TWITTER = 'twitter';
	const GOOGLE = 'google';
	const GOOGLE_PLUS = 'google_plus';
	const GOOGLE_ANALYTICS = 'google_analytics';

	/**
	 * @var array
	 */
	private $configuration;

	/**
	 * Constructor
	 * @param array $aConfiguration
	 */
	public function __construct(array $aConfiguration){
		$this->configuration = $aConfiguration;
	}


	/**
	 * Check if service exists
	 * @param string $sService
	 * @return boolean
	 */
	private static function serviceExists($sService){
		switch($sService){
			case self::FACEBOOK:
			case self::GOOGLE:
			case self::GOOGLE_PLUS:
			case self::GOOGLE_ANALYTICS:
			case self::TWITTER:
				return true;
		}
		return false;
	}

	public function getServiceConfig($sService){
		if(!self::serviceExists($sService))throw new \Exception('Service is not valid : '.$sService);
		if(!isset($this->configuration[$sService]))throw new \Exception('Configuration for service '.$sService.' is not defined');
		return $this->configuration[$sService];
	}

	/**
	 * Return social service infos
	 * @param string $sService
	 * @throws \Exception
	 * @return string
	 */
	public function __invoke($sService){
		if(!self::serviceExists($sService))throw new \Exception('Service is not valid : '.$sService);
		if(!isset($this->configuration[$sService]))throw new \Exception('Configuration for service '.$sService.' is not defined');
		switch($sService){
			case self::GOOGLE_ANALYTICS:
				if(empty($this->configuration[self::GOOGLE_ANALYTICS]['id']))throw new \Exception('Google analytics id is not defined');
				return $this->getView()->inlineScript(\Zend\View\Helper\HeadScript::SCRIPT)->appendScript('
		        	var _gaq=[[\'_setAccount\',\''.$this->configuration[self::GOOGLE_ANALYTICS]['id'].'\'],[\'_trackPageview\']];
					(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
					g.src=(\'https:\'==location.protocol?\'//ssl\':\'//www\')+\'.google-analytics.com/ga.js\';
					s.parentNode.insertBefore(g,s)}(document,\'script\'));
			    ');
		}

	}
}