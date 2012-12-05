<?php
namespace Application\View\Helper;
class EscapeJson extends \Zend\View\Helper\AbstractHelper{
	public function __invoke($sValue){
		return \Zend\Json\Json::encode($sValue);
	}
}