(function(global){
var Modal = global.Modal || {};
Modal.Popup = {
	Implements: [Options, Events],
	options:{
		'id':null,
		'container':null,
		'title':null,
		'close':true,
		'persist':false,
		'closeOnClickOut':false,
		'buttons' : []
	},
	
	/**
	 * @var HTMLElement
	 */
	element : null,
	
	/**
	 * @var Bootstrap.Popup
	 */
	popup : null,
	
	/**
	 * @var Array
	 */
	buttons : [],

	/**
	 * Constructor
	 * @param object oOptions
	 */
	initialize : function(oOptions){
		this.setOptions(oOptions);
		if(this.options.id == null)this.options.id = String.uniqueID();
		if((this.options.container = document.id(this.options.container)) == null)this.options.container = document.id(document.body).getElement('.container');
		this.buildPopup();
    },
    
    /**
     * Build popup
     * @return Modal.Popup
     */
    buildPopup : function(){
    	//Create popup html elements
    	this.element = new Element('div',{
    		'id':this.options.id,
    		'class':'modal fade BS.DismissPopup'
    	}).inject(this.options.container);
    	
    	var eHeader = new Element('div',{'class':'modal-header'}).inject(this.element),
    	eBody = new Element('div',{'class':'modal-body'}).inject(this.element),
    	eFooter = new Element('div',{'class':'modal-footer'}).inject(this.element);

    	//Header content
    	if('string' === typeof this.options.title){
    		var aElements = Elements.from(this.options.title);
    		if(aElements.length)eHeader.adopt(new Element('h3').adopt(aElements));
    		else eHeader.adopt(new Element('h3',{'html':this.options.title}));
    	}
    	else if(this.options.title instanceof Element)eHeader.adopt(new Element('h3').adopt(this.options.title));
    	else eHeader.adopt(new Element('h3',{'html':'&nbsp;'}));
    	if(this.options.close)eHeader.grab(new Element('a',{'html':'&times;','class':'close','title':oController.translate('close_modal')}),'top');
    	
    	//Body content
    	if('string' === typeof this.options.body)eBody.adopt(Elements.from(this.options.body));
    	else if(this.options.body instanceof Element)eBody.adopt(this.options.body);

    	//Create popup
    	this.popup = new Bootstrap.Popup(this.element,this.options);

    	if(this.options.buttons instanceof Array)this.options.buttons.each(function(oButton){
    		if(oButton instanceof Modal.Button)this.buttons.push(oButton.inject(this));
    		else{
    			oButton.modalPopup = this;
    			this.buttons.push(new Modal.Button(oButton));
    		}
    	}.bind(this));
    	this.popup.show();
    	if('string' === typeof this.options.url && this.options.url.length)this.popup.load(this.options.url,this.options);
    	return this.focusButton();
    }.protect(),
    
    /**
     * Focus buttons if one has to be focused
     * @return Modal.Popup
     */
    focusButton : function(){
    	this.buttons.some(function(oButton){
    		if(oButton.options.focus){
    			oButton.focus();
    			return true;
    		}
    	});
    	return this;
    }.protect()
};
Modal.Popup = new Class(Modal.Popup);
global.Modal = Modal;
})(this, document.id);