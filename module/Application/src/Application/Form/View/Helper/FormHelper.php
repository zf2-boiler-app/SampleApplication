<?php
namespace Application\Form\View\Helper;
class FormHelper extends \TwbBundle\Form\View\Helper\TwbBundleForm implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	/**
	 * @var \Zend\Http\Request
	 */
	protected $request;

	/**
	 * @var \Application\View\Helper\EscapeJsonHelper
	 */
	protected $escapeEscapeJsonHelper;

	/**
	 * @var \Zend\View\Helper\EscapeJs
	 */
	protected $escapeJsHelper;

	/**
	 * @return \Zend\Http\Request
	 */
	protected function getRequest(){
		if(!$this->request){
			$oRequest = $this->getServiceLocator()->getServiceLocator()->get('Request');
			if($oRequest)$this->setRequest($oRequest);
			else throw new \Exception('Request is undefined');
		}
		return $this->request;
	}

	/**
	 * @param \Zend\Http\Request $oRequest
	 * @return \Application\Form\View\Helper\FormHelper
	 */
	public function setRequest(\Zend\Http\Request $oRequest){
		$this->request = $oRequest;
		return $this;
	}

	/**
	 * Invoke as function
	 * @see \Zend\Form\View\Helper\Form::__invoke()
	 * @param null|\Zend\Form\FormInterface $oForm
     * @param string $sFormLayout : default 'horizontal'
	 * @return \TwbBundle\Form\View\Helper|string
	 */
	public function __invoke(\Zend\Form\FormInterface $oForm = null, $sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL,$bAjax = false){
		if($oForm)return $this->render($oForm,$sFormLayout,$bAjax);
		return $this;
	}

	/**
     * Render a form from the provided $oForm,
     * @see \Zend\Form\View\Helper\Form::render()
     * @param \Zend\Form\FormInterface $oForm
     * @param string $sFormLayout : default 'horizontal'
     * @param boolean $bAjax : default false
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm, $sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL,$bAjax = false){
		return $bAjax?$this->renderForAjax($oForm,$sFormLayout):parent::render($oForm,$sFormLayout);
	}

	/**
	 * Render form with ajax submit
	 * @see \Zend\Form\View\Helper\Form::render()
	 * @param \Zend\Form\FormInterface $oForm
	 * @param string $sFormLayout : default 'horizontal'
	 * @return string
	 */
	public function renderForAjax(\Zend\Form\FormInterface $oForm, $sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL,$bAjax = false){
		$sAfter = '
			if(document.id){
				try{
		';
		if(!$oForm->getAttribute('action'))$oForm->setAttribute('action',$this->getRequest()->getUri()->normalize());
		if(!$oForm->getAttribute('id'))$oForm->setAttribute('id',$oForm->getName());
		if($oForm->getAttribute('enctype') === 'multipart/form-data')$sAfter .= '
					var eForm = document.id('.$this->getEscapeJsonHelper()->__invoke($oForm->getAttribute('id')).');
					eForm.iFrameFormRequest({
				        onRequest: function(){
							if(eForm.validate())eForm.spin();
						},
						onComplete: function(sText){
							var sJavascript = null;
							var sHtml =	sText.stripScripts(function(sScript){
								sJavascript = sScript;
							});
							var aMatches = sHtml.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
							if(aMatches)sHtml = aMatches[1];
				           	eForm.getParent().empty().set(\'html\',sHtml);
							if(sJavascript)Browser.exec(sJavascript);
							eForm.unspin();
							window.behavior.apply(document.body,true);
				        },
				        onFailure: function(){
				        	alert(oController.translate(\'error_occurred\'));
				        }
				    });
		';
		else{
			$sAfter .= '
					var eForm = document.id('.$this->getEscapeJsonHelper()->__invoke($oForm->getAttribute('id')).');
					eForm.get(\'validator\').addEvent(\'formValidate\',function(bIsValid){
						if(bIsValid)new Form.Request(eForm,eForm.getParent()).send();
					});
			';
			$oForm->setAttribute('onsubmit','return false;');
		}
		$sAfter .= '
				}
			    catch(oException){
					if(oController != null)alert('.$this->getEscapeJsonHelper()->__invoke($this->getTranslator()->translate('error_occurred')).');
	    		}
			}
		';
		return parent::render($oForm,$sFormLayout).PHP_EOL.'<script type="text/javascript">'.$sAfter.'</script>';
	}

	/**
	 * Retrieve the escapeJson helper
	 * @return \Application\View\Helper\EscapeJsonHelper
	 */
	protected function getEscapeJsonHelper(){
		if($this->escapeEscapeJsonHelper)return $this->escapeEscapeJsonHelper;
		if(method_exists($this->view, 'plugin'))$this->escapeEscapeJsonHelper = $this->view->plugin('escapeJson');
		if(!$this->escapeEscapeJsonHelper instanceof \Application\View\Helper\EscapeJsonHelper)$this->escapeEscapeJsonHelper = new \Application\View\Helper\EscapeJsonHelper();
		return $this->escapeEscapeJsonHelper;
	}

	/**
	 * Retrieve the escapeJs helper
	 * @return \Zend\View\Helper\EscapeJs
	 */
	protected function getEscapeJsHelper(){
		if($this->escapeJsHelper)return $this->escapeJsHelper;
		if(method_exists($this->view, 'plugin'))$this->escapeJsHelper = $this->view->plugin('escapejs');
		if(!$this->escapeJsHelper instanceof \Zend\View\Helper\EscapeJs)$this->escapeJsHelper = new  \Zend\View\Helper\EscapeJs();
		return $this->escapeJsHelper;
	}
}