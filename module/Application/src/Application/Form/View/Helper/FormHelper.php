<?php
namespace Application\Form\View\Helper;
class FormHelper extends \DluTwBootstrap\Form\View\Helper\FormTwb{
	
	/**
	 * Renders a quick form
	 * @param Form $form
	 * @param string|null $formType
	 * @param array $displayOptions
	 * @param bool $renderErrors
	 * @return string
	 */
	public function render(\Zend\Form\Form $form, $formType = null, array $displayOptions = array(), $renderErrors = true){
		$aDisplayOptions['class'] = $this->genUtil->addWords($displayOptions['class'], 'FormValidator ');
		return parent::render($form,$formType,$displayOptions,$renderErrors);
	}
}