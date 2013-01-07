//Override Bootstrap.Popup
Bootstrap.Popup.implement({
	'setTitle':function(sTitle){
		this.element.getElement('.modal-header h3').set('html',sTitle);
		return this;
	},
	'load':function(sUrl,oOptions){
		var that = this;
		this.spin();
		if(oOptions == null || 'object' !== typeof oOptions)oOptions = {};
		
		var fSuccess;
		if('function' === typeof oOptions.onSuccess){
			fSuccess =  oOptions.onSuccess;
			delete oOptions.onSuccess;
		}
		
		var eBody = this.element.getElement('.modal-body');
		new Request.HTML(Object.merge({
			'method':'get',
			'data':eBody,
			'update':eBody,
			'url':sUrl,
			'onSuccess':function(){
				this.unspin();
				if(fSuccess != null)fSuccess(this);
			}.bind(this),
			'onFailure' : function(){
				if(that.animating && that.visible)that.addEvent('show',that.hide.bind(that));
				else that.hide();
    		}
		},oOptions)).send();
		return this;
	},
	'spin':function(){
		this.element.getElement('.modal-body').spin({
			'class':'spinner spinner-mask',
			'destroyOnHide':true,
			'containerPosition':{'offset':{'x':-2,'y':0}}
		});
		return this;
	},
	'unspin':function(){
		this.element.getElement('.modal-body').unspin();
		return this;
	},

	_makeMask: function(){
		if(this.options.mask){
			if(!this._mask){
				this._mask = new Element('div.modal-backdrop');
				if(this.options.closeOnClickOut)document.id(this._mask).addEvent('click',this.bound.hide);
				if(this._checkAnimate())this._mask.addClass('fade');
			}
		}
		else if(this.options.closeOnClickOut)document.id(document.body).addEvent('click', this.bound.hide);
		return this;
	}
});