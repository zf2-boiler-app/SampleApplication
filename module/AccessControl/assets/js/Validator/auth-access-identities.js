Form.Validator.addAllThese([
	['emailIsAvailable',{
		'errorMsg': function(eElement){
			return eElement.retrieve('email-available');
		},
		'test': function(eElement){
			return eElement.retrieve('email-available',true) === true;
		}
	}],
	['usernameIsAvailable',{
		'errorMsg': function(eElement){
			return eElement.retrieve('username-available');
		},
		'test': function(eElement){
			return eElement.retrieve('username-available',true) === true;
		}
	}]
]);