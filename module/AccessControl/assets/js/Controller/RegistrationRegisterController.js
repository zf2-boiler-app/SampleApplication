var UserControllerUserRegister = {
	Extends: Controller,
	
	/**
	 * @var int : setTimeout id
	 */
	timer : null,
	
	/**
	 * @param HTMLElement eUserEmail
	 * @return UserControllerUserRegister
	 */
	checkUserEmailAvailability : function(eUserEmail){
		eUserEmail = document.id(eUserEmail);
		if(eUserEmail == null)throw 'User email input is undefined';
		//Remove timer
		this.timer = null;
		var oValidator = eUserEmail.getParent('form').get('validator'), sEmail = eUserEmail.get('value');
		if(!sEmail.length || !oValidator.test('validate-email',eUserEmail))return this;
		
		this.timer = setTimeout(function(){
			//Set input is loading
			eUserEmail.setLoading();
			new Request.JSON({
				'url':this.url('User/checkuseremailavailability'),
				'data':{'email':sEmail},
				'onSuccess':function(oResponse){
					var bAvailable = oResponse.available === true;
					//Display email availability checked
					if(!bAvailable)eUserEmail.removeClass('validation-passed');
					eUserEmail.store('email-available',oResponse.available).setLoading('icon-'+(bAvailable?'ok':'ban-circle')).fireEvent('change');
				}.bind(this)
			}).send();
			this.timer = null;
		}.bind(this),250);
		return this;
	}
};
UserControllerUserRegister = new Class(UserControllerUserRegister);