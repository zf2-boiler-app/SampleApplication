<?php
namespace Application\Form\View\Helper;
class FormElementErrors extends \Zend\Form\View\Helper\FormElementErrors{

	/**
	 * Render validation errors for the provided $oElement
	 * @see \Zend\Form\View\Helper\FormElementErrors::render()
	 * @param \Zend\Form\ElementInterface $oElement
     * @param array $aAttributes
     * @return string
	 */
    public function render(\Zend\Form\ElementInterface $oElement, array $aAttributes = array()){
		if(isset($aAttributes['class'])){
			if(!preg_match('/(\s|^)advice(\s|$)/',$aAttributes['class']))$aAttributes['class'] .= ' advice';
		}
		else $aAttributes['class'] .= 'advice';
    	return parent::render($oElement,$aAttributes);
    }
}