Element.implement({
	/**
	 * Add an append icon to an input element
	 * @param string
	 * @return HTMLElement
	 */
	setAppendIcon : function(sIconClass){
		if(!this.getParent().hasClass('input-append'))new Element('div',{'class':'input-append'}).adopt(
			new Element('span',{'class':'add-on'})
			.adopt(new Element('i',{'class':sIconClass}))
		).inject(this,'before').grab(this,'top');
		else{
			var eAddOn;
			if((eAddOn = this.getNext('.add-on')) != null)eAddOn.getElement('i').className = sIconClass;
			else new Element('span',{'class':'add-on'}).adopt(new Element('i',{'class':sIconClass})).inject(this,'after')
		}
		return this;
	},
	/**
	 * Remove an append from an input element
	 * @param string
	 * @return HTMLElement
	 */
	unsetAppendIcon : function(){
		var eParent;
		if((eParent = this.getParent()) != null && eParent.hasClass('input-append')){
			this.inject(eParent,'before');
			eParent.destroy();
		}
		return this;
	},
	
	/**
	 * Display input loading state
	 * @param string|boolean : (optionnal) if true or null set input loading, else stop loading, if string swap append className
	 * @return HTMLElement
	 */
	setLoading : function(sStopLoading){
		if(sStopLoading == null || sStopLoading === true)return this.setStyle('cursor','wait').setAppendIcon('icon-loading');
		else if('string' === typeof sStopLoading)return this.setStyle('cursor','inherit').setAppendIcon(sStopLoading);
		else return this.setStyle('cursor','inherit').unsetAppendIcon();
	}
});