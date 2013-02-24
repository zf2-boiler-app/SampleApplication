(function(){
	Behavior.addGlobalFilters({
		'Form.PasswordStrength': {
			defaults: {},
			setup: function(eElement, api){
				var options = Object.cleanValues(
					api.getAs({})
				);
				if(!eElement.retrieve('Form.PasswordStrength')){
					eElement.store('Form.PasswordStrength',new Form.PasswordStrength(eElement,{
						'height':0,
						'onUpdate' : function(eElement, iStrength, iThreshold){
							var eHelpInline = eElement.getNext('.help-inline');
							if(iStrength === 0){
								eElement.getParent('.control-group').removeClass('warning').removeClass('info').removeClass('success');
								if(eHelpInline != null)eHelpInline.destroy();
							}
							else{
								var sText = '';
								var iMin = iThreshold / 3;
								if(iStrength < iMin){
									if('undefined' !== typeof oController)sText = oController.translate('Password strength very week');
									eElement.getParent('.control-group').removeClass('info').removeClass('success').addClass('warning');
								}
								else if(iStrength < iMin*2){
									if('undefined' !== typeof oController)sText = oController.translate('Password strength good');
									eElement.getParent('.control-group').removeClass('warning').removeClass('success').addClass('info');
								}
								else{
									if('undefined' !== typeof oController)sText = oController.translate('Password strength strong');
									eElement.getParent('.control-group').removeClass('warning').removeClass('info').addClass('success');
								}
								
								if(eHelpInline == null)eElement.grab(new Element('span',{'class':'help-inline','html':sText}),'after');
								else eHelpInline.set('html',sText);
							}
						}
					}));
				}
				return eElement;
			}
		}
	});
})();