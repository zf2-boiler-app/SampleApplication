Form.Validator.addAllThese([
	['validate-class-week-schedule', {
		errorMsg: function(){
			return oController.translate('Invalid schedule given.');
		},
		test: function(eElement){
			var ePreviousInput = eElement.getPrevious('.validate-class-week-schedule');
			if(ePreviousInput != null && !Form.Validator.getValidator('validate-class-week-schedule').test(ePreviousInput))return true;
			if(Form.Validator.getValidator('IsEmpty').test(eElement))return false;
			//Check schedule format
			return eElement.get('value').test(/^([0-9]|[0-1][0-9]|[2][0-3])h([0-5][0-9])$/);
		}
	}],
	['validate-file-extension', {
		errorMsg: function(eElement){
			return oController.translate("File '%value%' has a false extension").replace('%value%',eElement.get('value'));
		},
		test: function(eElement,aProperties){
			var sFile = eElement.get('value');
			if(!sFile.length)return true;
			if(aProperties['validate-file-extension'] != null && 'string' === typeof aProperties['validate-file-extension'] && aProperties['validate-file-extension'].length){
				var aExtensions = aProperties['validate-file-extension'].split(',');
				if(!aExtensions.length)return true;
				else{
					var sExtension = sFile.split('.').pop();
					return aExtensions.some(function(sValidExtension){
						return sExtension === sValidExtension;
					});
				}
			}
			else return true;
		}
	}]
]);