(function(){
	Behavior.addGlobalFilters({
		'Meio.Mask': {
			defaults: {},
			setup: function(eElement, api){
				var options = Object.cleanValues(
					api.getAs({})
				);
				eElement.getElements('[data-meiomask]').each(function(eInput){eInput.meiomask(eInput.get('data-meiomask'));});
				return eElement;
			}
		},
	});
})();