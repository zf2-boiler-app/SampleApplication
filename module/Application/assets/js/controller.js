var Controller = {
	Implements: [Options, Events],
	options:{
		'locale':null,
		'texts':{},
		'routes':{}
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
		window.delegator = new Delegator({'getBehavior':function(){return window.behavior;}}).attach(document.body);

		//Init locale
		if('string' === typeof this.options.locale && this.options.locale.length && Locale != null && 'function' === typeof Locale.use)Locale.use(this.options.locale);

		//Overide request
		Class.refactor(Request,{
			'onFailure': function(oXhr){
				var sError;
				if(oXhr != null && oXhr.responseText != null){
    				try{
    					var oResponse = JSON.decode(oXhr.responseText,true);
    					if(oResponse != null && 'string' === typeof oResponse.error)sError = oResponse.error;
    				}
    				catch(oException){}
    			}
    			
				if(!sError && this.xhr != null)switch(this.xhr.status){
    				case 404:
    					sError = that.translate('404_error');
    					break;
    			}
				alert(sError?sError:that.translate('error_occurred'));
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
    			if(oResponse == null)alert(that.translate('error_occurred'));
    			else if('string' === typeof oResponse.error)alert(oResponse.error);
    			else this.fireEvent('complete', [oResponse]).fireEvent('success', [oResponse]).callChain();
    		},
    		'onError' : function(){
    			alert(that.translate('error_occurred'));
    			this.fireEvent('error',arguments);
    		},
		});

		Class.refactor(Request.HTML,{
			'success': function(text){
				this.previous(text);
				window.behavior.apply(this.options.container,true);
			}
		});
	},

	/**
	 * @param string sKey : (optionnal)
	 * @return string|object
	 */
	translate : function(sKey){
		if('string' !== typeof sKey)return this.options.texts;
		return this.options.texts[sKey] == null?sKey:this.options.texts[sKey];
	},
	
	/**
	 * @param string sRoute
	 * @throws Exception
	 * @return string
	 */
	url : function(sRoute){
		if(this.options.routes[sRoute] == null)throw 'Undefined route : '+sRoute;
		return this.options.routes[sRoute];
	}
};
Controller = new Class(Controller);