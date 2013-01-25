Form.Validator.addAllThese([
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