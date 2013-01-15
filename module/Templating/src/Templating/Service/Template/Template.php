<?php
namespace Templating\Service\Template;
class Template{
	/**
	 * @var \Templating\Service\Template\TemplateConfiguration
	 */
	protected $configuration;

	/**
	 * @var array
	 */
	protected $children;

	/**
	 * Constructor
	 */
	private function __construct(\Templating\Service\Template\TemplateConfiguration $oConfiguration){
		$this->configuration = $oConfiguration;
	}

	/**
	 * @throws \Exception
	 * @return \Templating\Service\Template\TemplateConfiguration
	 */
	public function getConfiguration(){
		if($this->configuration instanceof \Templating\Service\TemplatingConfiguration)return $this->configuration;
		throw new \Exception('Configuration is undefined');
	}

	/**
	 * @return array
	 */
	public function getChildren(){
		if(!is_array($this->children))foreach($this->getConfiguration()->getChildren() as $sChildrenName => $oChildrenConfiguration){
			if(!is_string($sChildrenName))throw new \Exception('Children Name expects string, '.gettype($sChildrenName).' given');
			if($oChildrenConfiguration instanceof \Traversable)$oChildrenConfiguration = \Zend\Stdlib\ArrayUtils::iteratorToArray($oChildrenConfiguration);

			if(is_array($oChildrenConfiguration))$oChildrenConfiguration = new \Templating\Service\Template\TemplateConfiguration($oChildrenConfiguration);
			elseif(is_string($oChildrenConfiguration))$oChildrenConfiguration = new \Templating\Service\Template\TemplateConfiguration(array(
				'layout' => $oChildrenConfiguration
			));
			if(!($oChildrenConfiguration instanceof \Templating\Service\Template\TemplateConfiguration))throw new \Exception(sprintf(
				'% expects an array, Traversable object, string or \Templating\Service\Template\TemplateConfiguration object ; received "%s"',
				__METHOD__,
				(is_object($oOptions)?get_class($oOptions):gettype($oOptions))
			));
			$this->children[$sChildrenName] = new \Templating\Service\Template\Template($oChildrenConfiguration);
		}
		return $this->children;
	}
}