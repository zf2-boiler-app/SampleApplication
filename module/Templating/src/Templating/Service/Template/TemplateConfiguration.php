<?php
namespace Templating\Service\Template;
class TemplateConfiguration extends \Zend\Stdlib\AbstractOptions{
	/**
	 * @var string
	 */
	protected $layout;

	/**
	 * @var array
	 */
	protected $children;

	/**
	 * @param string $sLayout
	 * @throws \Exception
	 * @return \Templating\Service\Template\TemplateConfiguration
	 */
	public function setLayout($sLayout){
		if(!is_string($sLayout))throw new \Exception('Layout expects string, '.gettype($sModule).' given');
		$this->layout = $sLayout;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return string
	 */
	public function getLayout(){
		if(is_string($this->layout))return $this->layout;
		throw new \Exception('Layout is undefined');
	}

	/**
	 * @param array $aChildren
	 * @throws \Exception
	 * @return \Templating\Service\Template\TemplateConfiguration
	 */
	public function setChildren(array $aChildren){
		$this->children = $aChildren;
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return array
	 */
	public function getChildren(){
		if(is_array($this->children))return $this->children;
		throw new \Exception('Children are undefined');
	}
}