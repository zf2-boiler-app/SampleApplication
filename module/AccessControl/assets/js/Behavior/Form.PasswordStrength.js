(function(){
	Behavior.addGlobalFilters({
		'Form.PasswordStrength': {
			defaults: {},
			setup: function(eElement, api){
				var options = Object.cleanValues(
					api.getAs({})
				);
				
				top.console.log(eElement);
				//eElement.getElements('[data-password-strength]').each(function(eInput){eInput.meiomask(eInput.get('data-password-strength'));});
				return eElement;
			}
		},
	});
})();