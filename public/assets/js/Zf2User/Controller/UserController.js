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
		var sEmail = eUserEmail.get('value');
		this.timer = null;
		if(!sEmail.length)return this;
		this.timer = setTimeout(function(){
			new Request.JSON({
				'url':this.url('zf2user/checkuseremailavailability'),
				'onSuccess':function(oResponse){
					
				}
			}).send();
			this.timer = null;
		}.bind(this),250);
		return this;
	}
};
ZF2UserControllerUser = new Class(ZF2UserControllerUser);