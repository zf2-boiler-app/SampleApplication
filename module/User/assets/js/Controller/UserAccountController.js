var UserControllerUserAccount = {
	Extends: Controller,
	
	/**
	 * @return UserControllerUserAccount
	 */
	changeUserAvatar : function(){
		new Modal.Popup({
			'title':this.translate('change_avatar'),
			'url':this.url('User/change-avatar')
		});
		return this;
	},
	
	/**
	 * @param string sAvatar
	 * @return UserControllerUserAccount
	 */
	setUserAvatar : function(sAvatar){
		var eUserAvatar = document.id('user-avatar');
		if(eUserAvatar == null)throw 'User avatar image is undefined';
		eUserAvatar.set('src','data:image/png;base64,'+sAvatar);
		return this;
	},
	
	/**
	 * @return UserControllerUserAccount
	 */
	changeUserPassword : function(){
		new Modal.Popup({
			'title':this.translate('change_password'),
			'url':this.url('User/change-password')
		});
		return this;
	},
	
	/**
	 * @return UserControllerUserAccount
	 */
	changeUserEmail : function(){
		new Modal.Popup({
			'title':this.translate('change_email'),
			'url':this.url('User/change-email')
		});
		return this;
	},
};
UserControllerUserAccount = new Class(UserControllerUserAccount);