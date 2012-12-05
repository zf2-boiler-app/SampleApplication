Mask.implement({
	position: function(){
		this.resize(this.options.width, this.options.height);
		this.element.position(Object.merge({
			'relativeTo': this.target,
			'position': 'topLeft',
			'ignoreMargins': !this.options.maskMargins,
			'ignoreScroll': this.target == document.body,
		},(this.options.containerPosition == null?{}:this.options.containerPosition)));
		return this;
	},
});