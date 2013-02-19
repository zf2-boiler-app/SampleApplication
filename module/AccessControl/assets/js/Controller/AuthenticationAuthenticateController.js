var UserControllerUserLogin = {
	Extends: Controller,
	
	/**
	 * @param string eUserEmail
	 * @return UserControllerUserLogin
	 */
	sendConfirmationEmail : function(sUserEmail){
		if('string' !== typeof sUserEmail)throw 'User email expects string';
		new Modal.Popup({
			'title':this.translate('resend_confirmation_email'),
			'url':this.url('User/resend-confirmation-email'),
			'data':{'email':sUserEmail},
			'method':'post'
		});		
	}
};
UserControllerUserLogin = new Class(UserControllerUserLogin);