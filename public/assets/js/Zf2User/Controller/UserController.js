var ZF2UserControllerUser = {
	Extends: Controller,
	
	timer : null,
	
	/**
	 * @param HTMLElement eUserEmail
	 * @return ZF2UserControllerUser
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
				'url':this.url('zf2user/checkuseremailavailability'),
				'data':{'email':sEmail},
				'onSuccess':function(oResponse){
					var bAvailable = oResponse.available === true;
					//Display email availability checked
					if(!bAvailable)eUserEmail.removeClass('validation-passed');
					eUserEmail.store('email-available',bAvailable).setLoading('icon-'+(bAvailable?'ok':'ban-circle')).fireEvent('change');
				}.bind(this)
			}).send();
			this.timer = null;
		}.bind(this),250);
		return this;
	},
	
	sendConfirmationEmail : function(sUserEmail){
		if('string' !== typeof sUserEmail)throw 'User email expects string';
		var eBody = document.id(document.body).spin();
		new Request.JSON({
			'url':this.url('zf2user/resend-confirmation-email'),
			'data':{'email':sUserEmail},
			'onSuccess':function(){
				alert(this.translate('email_confirmation_sent'));
			}.bind(this),
			'onComplete':function(){
				eBody.unspin();
			},
			'onError':function(){
				eBody.unspin();
			}
		}).send();
	},
	
	changeUserPassword : function(){
		new Modal.Popup({
			'title':this.translate('change_password'),
			'url':this.url('zf2user/change-password')
		});
	},
};
ZF2UserControllerUser = new Class(ZF2UserControllerUser);