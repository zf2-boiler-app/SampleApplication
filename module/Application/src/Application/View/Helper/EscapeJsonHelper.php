<?php
namespace Application\View\Helper;
class EscapeJsonHelper extends \Zend\View\Helper\AbstractHelper{
	public function __invoke($sValue){
		return \Zend\Json\Json::encode($sValue);
	}
}