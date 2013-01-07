//Override Bootstrap.Popup
Bootstrap.Popup.implement({
	'setTitle':function(sTitle){
		this.element.getElement('.modal-header h3').set('html',sTitle);
		return this;
	},
	'load':function(sUrl,oOptions){
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
				if(this.animating && this.visible)this.addEvent('show',this.hide.bind(this));
				else this.hide();
    		}.bind(this)
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
	
	show: function(){
		if (this.visible || this.animating) return;
		this.element.addEvent('click:relay(.close, .dismiss)', this.bound.hide);
		if (this.options.closeOnEsc) document.addEvent('keyup', this.bound.keyMonitor);
		this._makeMask();
		if(this._mask.getParent() == null)this._mask.inject(document.body);
		this.animating = true;
		if (this.options.changeDisplayValue) this.element.show();
		if (this._checkAnimate()){
			this.element.offsetWidth; // force reflow
			this.element.addClass('in');
			this._mask.addClass('in');
		} else {
			this.element.show();
			this._mask.show();
		}
		this.visible = true;
		this._watch();
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