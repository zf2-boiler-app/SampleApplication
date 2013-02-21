var AccessControlControllerRegistrationRegister = {
	Extends: Controller,
	
	/**
	 * @var int : setTimeout id
	 */
	emailTimer : null,
	
	/**
	 * @var int : setTimeout id
	 */
	nameTimer : null,
	
	/**
	 * @param HTMLElement eEmailIdentity
	 * @return AccessControlControllerRegistrationRegister
	 */
	checkEmailIdentityAvailability : function(eEmailIdentity){
		eEmailIdentity = document.id(eEmailIdentity);
		if(eEmailIdentity == null)throw 'Email identity input is undefined';
		//Remove timer
		this.emailTimer = null;
		var oValidator = eEmailIdentity.getParent('form').get('validator'), sEmail = eEmailIdentity.get('value');
		if(!sEmail.length || !oValidator.test('validate-email',eEmailIdentity))return this;
		
		this.emailTimer = setTimeout(function(){
			//Set input is loading
			eEmailIdentity.setLoading();
			new Request.JSON({
				'url':this.url('AccessControl/CheckEmailIdentityAvailability'),
				'data':{'email':sEmail},
				'onSuccess':function(oResponse){
					var bAvailable = oResponse.available === true;
					//Display email availability checked
					if(!bAvailable)eEmailIdentity.removeClass('validation-passed');
					eEmailIdentity.store('email-available',oResponse.available).setLoading('icon-'+(bAvailable?'ok':'ban-circle')).fireEvent('change');
				}.bind(this)
			}).send();
			this.emailTimer = null;
		}.bind(this),250);
		return this;
	},
	
	
	/**
	 * @param HTMLElement eUsernameIdentity
	 * @return AccessControlControllerRegistrationRegister
	 */
	checkUsernameIdentityAvailability : function(eUsernameIdentity){
		eUsernameIdentity = document.id(eUsernameIdentity);
		if(eUsernameIdentity == null)throw 'Username identity input is undefined';
		//Remove timer
		this.nameTimer = null;
		var oValidator = eUsernameIdentity.getParent('form').get('validator'), sUserName = eUsernameIdentity.get('value');
		if(!sUserName.length || !oValidator.test('validate-nospace',eUsernameIdentity))return this;
		
		this.nameTimer = setTimeout(function(){
			//Set input is loading
			eUsernameIdentity.setLoading();
			new Request.JSON({
				'url':this.url('AccessControl/CheckUsernameIdentityAvailability'),
				'data':{'username':sUserName},
				'onSuccess':function(oResponse){
					var bAvailable = oResponse.available === true;
					//Display username availability checked
					if(!bAvailable)eUsernameIdentity.removeClass('validation-passed');
					eUsernameIdentity.store('username-available',oResponse.available).setLoading('icon-'+(bAvailable?'ok':'ban-circle')).fireEvent('change');
				}.bind(this)
			}).send();
			this.nameTimer = null;
		}.bind(this),250);
		return this;
	}
};
AccessControlControllerRegistrationRegister = new Class(AccessControlControllerRegistrationRegister);