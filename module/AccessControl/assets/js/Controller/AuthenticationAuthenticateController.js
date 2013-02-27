var AccessControlControllerAuthentication = {
	Extends: Controller,
	
	/**
	 * @param string eUserEmail
	 * @return UserControllerUserLogin
	 */
	sendConfirmationEmail : function(sAuthAccessIdentity){
		if('string' !== typeof sAuthAccessIdentity)throw 'AuthAccess identity expects string';
		new Modal.Popup({
			'title':this.translate('resend_confirmation_email'),
			'url':this.url('AccessControl/ResendConfirmationEmail'),
			'data':{'auth_access_identity':sAuthAccessIdentity},
			'method':'post'
		});		
	}
};
AccessControlControllerAuthentication = new Class(AccessControlControllerAuthentication);