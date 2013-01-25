<?php
namespace Application\Form\View\Helper;
class FormHelper extends \DluTwBootstrap\Form\View\Helper\FormTwb{
	/**
	 * @var \Zend\Http\Request
	 */
	protected $request;

	/**
	 * Constructor
	 * @param \DluTwBootstrap\GenUtil $oGenUtil
	 * @param \DluTwBootstrap\Form\FormUtil $oFormUtil
	 */
	public function __construct(\DluTwBootstrap\GenUtil $oGenUtil, \DluTwBootstrap\Form\FormUtil $oFormUtil, \Zend\Http\Request $oRequest){
		parent::__construct($oGenUtil, $oFormUtil);
		$this->request = $oRequest;
	}

	/**
	 * @var \Application\View\Helper\EscapeJsonHelper
	 */
	protected $escapeEscapeJsonHelper;

	/**
	 * @see \DluTwBootstrap\Form\View\Helper\FormTwb::render()
	 * @param \Zend\Form\Form $oForm
	 * @param string $sFormType
	 * @param array $aDisplayOptions
	 * @param bool $bRenderErrors
	 * @return string
	 */
	public function render(\Zend\Form\Form $oForm, $sFormType = null, array $aDisplayOptions = array(), $bRenderErrors = true){
		return empty($aDisplayOptions['ajax'])
			?parent::render($oForm,$sFormType,$aDisplayOptions,$bRenderErrors)
			:$this->renderForAjax($oForm,$sFormType,$aDisplayOptions,$bRenderErrors);
	}

	/**
	 * Render form with ajax submit
	 * @param \Zend\Form\Form $oForm
	 * @param string $sFormType
	 * @param array $aDisplayOptions
	 * @param boolean $bRenderErrors
	 * @return string
	 */
	protected function renderForAjax(\Zend\Form\Form $oForm, $sFormType = null, array $aDisplayOptions = array(), $bRenderErrors = true){
		$sAfter = '
			<script type="text/javascript">
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
			</script>
		';
		return parent::render($oForm,$sFormType,$aDisplayOptions,$bRenderErrors).PHP_EOL.$sAfter;
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
	 * @return \Zend\Http\Request
	 */
	protected function getRequest(){
		return $this->request;
	}
}