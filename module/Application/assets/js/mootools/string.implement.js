String.implement({
    urlEncode : function(){
		var output = '';
		var x = 0;
		var regex = /(^[a-zA-Z0-9_.]*)/;
		while(x < this.length){
			var match = regex.exec(this.substr(x));
			if(match != null && match.length > 1 && match[1] != ''){
				output += match[1];
				x += match[1].length;
			}
			else {
				if(this[x] == ' ')output += '+';
				else{
					var charCode = this.charCodeAt(x);
					var hexVal = charCode.toString(16);
					output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
				}
				x++;
			}
		}
		return output;
	},
	ltrim : function(charlist){
	    return this.replace(new RegExp('^['+(!charlist?' \\s\u00A0':(charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'$1'))+']+','g'),'');
	},
	rtrim : function(charlist){
	    return this.replace(new RegExp('['+(!charlist?' \\s\u00A0':(charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'\\$1'))+']+$','g'),'');
	}
});