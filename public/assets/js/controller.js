var Controller = {
	Implements: [Options, Events],
	options:{
		'locale':null,
		'texts':{}
	},

	/**
	 * Constructor
	 * @param object oOptions
	 */
	initialize : function(oOptions){
		this.setOptions(oOptions);

		var that = this;

		//Init behaviors
		window.behavior = new Behavior().apply(document.body);
		window.delegator = new Delegator({getBehavior:function(){return window.behavior;}}).attach(document.body);

		//Init locale
		if('string' === typeof this.options.locale && this.options.locale.length && Locale != null && 'function' === typeof Locale.use)Locale.use(this.options.locale);

		//Overide request
		Class.refactor(Request,{
			'onFailure': function(oXhr){
				if(oXhr != null && oXhr.responseText != null){
    				try{
    					var oResponse = JSON.decode(oXhr.responseText,true);
    					if(oResponse != null && 'string' === typeof oResponse.error){
    						alert(oResponse.error);
    						return this.fireEvent('complete').fireEvent('failure', this.xhr);
    					}
    				}
    				catch(oException){}
    			}
    			alert(that.translate('app_error'));
				return this.fireEvent('complete').fireEvent('failure', this.xhr);
			}
		});

		Class.refactor(Request.JSON,{
			'success': function(text){
				var json;
				try {
					json = this.response.json = JSON.decode(text, this.options.secure);
				} catch (error){
					this.onError(text, error);
					return;
				}
				if (json == null) this.onFailure();
				else this.onSuccess(json, text);
			},
			'onSuccess':function(oResponse){
    			if(oResponse == null)alert(that.translate('app_error'));
    			else if('string' === typeof oResponse.error)alert(oResponse.error);
    			else this.fireEvent('complete', [oResponse.datas]).fireEvent('success', [oResponse.datas]).callChain();
    		},
    		'onError' : function(){
    			alert(that.translate('app_error'));
    			this.fireEvent('error',arguments);
    		},
		});

		Class.refactor(Request.HTML,{
			'success': function(text){
				this.previous(text);
				window.behavior.apply(document.body);
			}
		});
	},

	translate : function(sKey){
		if('string' !== typeof sKey)return this.options.texts;
		return this.options.texts[sKey] == null?sKey:this.options.texts[sKey];
	}
};
Controller = new Class(Controller);