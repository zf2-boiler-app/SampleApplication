(function(global){
var Modal = global.Modal || {};
Modal.Button = {
	Implements: [Options, Events],
	options:{
		'label': null,
		'action': null,
		'focus': false,
		'type': '',
		'modalPopup':null
	},
	
	/**
	 * @var HTMLElement
	 */
	element : null,
	
	/**
	 * Constructor
	 * @param object oOptions
	 */
	initialize : function(oOptions){
		this.setOptions(oOptions);
		if('string' !== typeof this.options.label || 'function' !== typeof this.options.action)throw 'Modal Button options are not valid';
		this.buildButton();
    },
	
	/**
	 * Build button 
	 * @return Modal.Button
	 */
	buildButton : function(){
		this.element = new Element('button',{
			'class':'btn'+('string' === typeof this.options.type?' '+oButton.type:''),
			'title':this.options.label,
			'html':this.options.label,
			'events':{'click':this.options.action}
		});
		if(this.options.modalPopup instanceof Modal.Popup)this.inject(this.options.modalPopup);
	}.protect(),
	
	/**
	 * Inject button into modal popup 
	 * @return Modal.Button
	 */
	inject : function(oModalPopup){
		if(!(oModalPopup instanceof Modal.Popup))throw 'Modal expects instance of Modal.Popup';
		//Inject button into modal footer and bind action to modal object
		this.element.inject(oModalPopup.element.getElement('.modal-footer')).$events.click.map(function(fFunction){
			return fFunction.bind(this);
		}.bind(oModalPopup));
		this.options.modalPopup = oModalPopup
		return this;
	},
	
	/**
	 * Focus button
	 * @return Modal.Button
	 */
	focus : function(){
		this.element.focus();
		return this;
	}
};
Modal.Button = new Class(Modal.Button);
global.Modal = Modal;
})(this, document.id);
