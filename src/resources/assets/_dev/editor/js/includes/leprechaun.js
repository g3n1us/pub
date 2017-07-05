	//auto add csrf protection
/*
	$('form').each(function(){
		if(!$(this).find('[name="_token"]').length){
			var csrftokenfield = $('<input />');   
			csrftokenfield.attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content'));
			$(csrftokenfield).appendTo($(this));
		}
	});			
*/

var leprechaun_loaders = function(config){
	var _this = this;
	_this.rawtemplates = raw_handlebars_templates;
			
	_this.templates = handlebars_templates;

	_this.outputAllTemplates = function(data, data_selector, callback){
// setTimeout(function(){
		if(typeof data === "undefined" || data== null) data = {};
		if(typeof data_selector === "undefined" || data_selector== null) data_selector = 'data-handlebars_template';
		var selector_jqobj = $('[' + data_selector + ']');
		selector_jqobj.not('.finished').each(function(){
			console.log($(this));
			
			$(this).data('in_progress', true);
			var filter = $(this).data('filter');
			var subdir = $(this).data('subdir');
			var type = $(this).data('type');
			if(typeof type === "undefined" && $('#' + $(this).attr(data_selector)).length) type = $('#' + $(this).attr(data_selector)).data('default');
			var sortby = $(this).data('sortby');
			var sortasc = $(this).data('asc');		
			var datasource = $(this).data('source');
			var datasourceurl = $(this).data('sourceurl');
			var datakey = $(this).data('key');
			var dataonclick = $(this).data('onclick');
			var usedata = data;
			if(typeof dataonclick !== "undefined"){
				$(this).html('<button type="button" class="btn btn-primary onclicktemplateloader" onclick="lep_loader.independentOutputTemplate($(this).parent())">Load</button>').addClass('finished');
				return;
			}
			if(typeof datasourceurl !== "undefined"){
				$this = $(this);
				$this.addClass('finished');
				$.ajax({
					url: datasourceurl,
					dataType: 'json',
					success: function(data) {
						usedata = data;
						if($this.data('append'))
							$this.append(_this.outputTemplate($this.attr(data_selector), usedata)).addClass('finished');			
						else if($this.data('prepend'))
							$this.prepend(_this.outputTemplate($this.attr(data_selector), usedata)).addClass('finished');			
						else
							$this.html(_this.outputTemplate($this.attr(data_selector), usedata)).addClass('finished');
					}
				});
				return;	
			}
			
			if(typeof datasource !== "undefined"){
				var sourcejson = $.trim($(datasource).text());
				usedata = JSON.parse(sourcejson);
				$(datasource).html('');
			}	
	
			if(typeof type !== "undefined"){
				usedata = usedata[type];
			}
	
			if(typeof subdir !== "undefined"){
				usedata = usedata[subdir];
			}
	
			if(typeof filter !== "undefined"){
				var returndata = [];
				if(typeof datakey === "undefined") datakey = 'id';
				
				$.each(usedata, function(key, value){
					if(value[datakey] == filter) returndata.push(value);
				});
				
				if(!returndata.length) returndata = {empty: true};
				else if(returndata.length == 1){
					if(typeof $(this).data('array') === "undefined"){
						returndata = returndata[0];
					}
				}
			}
			else returndata = usedata;
			
			if(typeof sortby !== "undefined"){
				// use data-attributes to set sorting. data-asc is boolean to sort ascending, default is descending
	
				returndata = returndata.sort(function (a, b) {
					return new Date(b[sortby]) - new Date(a[sortby])
				});				
				
				if(typeof sortasc !== "undefined"){
					returndata = returndata.reverse();
				}
			}
			
			if($(this).data('append'))
				$(this).append(_this.outputTemplate($(this).attr(data_selector), returndata)).addClass('finished');			
			else if($(this).data('prepend'))
				$(this).prepend(_this.outputTemplate($(this).attr(data_selector), returndata)).addClass('finished');			
			else
				$(this).html(_this.outputTemplate($(this).attr(data_selector), returndata)).addClass('finished');
			
			tokenize();
			
			if(callback){
				callback();
			}
			
		});
		
		// Add required
		$('.form-group.required, [data-required]').find(':input').attr('required', 'required');
		
			// Fill in existing values
		$('[data-value]').each(function(){
			var datavalue = $(this).data('value');
			if(datavalue != "" && typeof datavalue !== "undefined"){
				$(this).find(':input').val($(this).data('value'));
			}
			
		});
	
		if(selector_jqobj.not('.finished').length) _this.outputAllTemplates(); // if a template contained a nested template launcher, play it again
		else selector_jqobj.removeClass('finished');
// }, 1);	
	} // close of outputAllTemplates(data)
	
	
	
	_this.outputTemplate = function(templateID, data){
		if(typeof templateID !== "undefined" && typeof data !== "undefined"){
			if(typeof _this.templates[templateID] !== "undefined"){
				return _this.templates[templateID](data);
			}
			else if($('script#' +  templateID).length){
				var template = Handlebars.compile($('script#' +  templateID).html());
				return template(data);
			}
			else return false;
		}
		else return false;
	}
	
	
	_this.independentOutputTemplate = function(jqobj){
		jqobj.html('<span class="text-center center-block h1"><i class="fa fa-2x fa-cog fa-spin fa-rotate"></i></span>');
		setTimeout(function(){
			var data_selector = 'data-handlebars_template';
			if(typeof jqobj.data('private_output') !== "undefined") data_selector = 'data-private_output';
			var usedata;
			if(typeof jqobj.data('source') !== "undefined"){
				var sourcejson = $.trim($(jqobj.data('source')).text());
				usedata = JSON.parse(sourcejson);
				$(jqobj.data('source')).html('');
			}
			else if(typeof jqobj.data('sourceurl') !== "undefined"){
				$.ajax({
					url: jqobj.data('sourceurl'),
					dataType: 'json',
					async: false,
					success: function(data) {
						console.log(data);
						usedata = data;
					}
				});	
			}
			else usedata = {};
			jqobj.html(_this.outputTemplate(jqobj.attr(data_selector), usedata));
			
		}, 100);
	}
	
}

var lep_loader = new leprechaun_loaders();
$(document).ready(function(){
	$('.carousel').carousel();
	
	Mousetrap.bind('right', function() {
		$('.carousel:first').carousel('next');
	});
	Mousetrap.bind('left', function() {
		$('.carousel:first').carousel('prev');
	});
	
	$('script[type*="template"]').each(function(){
		var thisid = $(this).attr('id');
		thisid = thisid.replace(/-/g, "_");
		var source  = $(this).html();
		if(typeof $(this).data('default') === "undefined") var usedata = false;
		else usedata = $(this).data('default');
		lep_loader.rawtemplates.push({"id": thisid, "source": source, "datasource": usedata});
		lep_loader.templates[thisid] = Handlebars.compile(source);
	});
	lep_loader.outputAllTemplates(window.handlebars_template_data);
});

	


// data-template magic. These are functions ONLY! They are fired in other scripts. Don't fire them here!



// Humps:

// =========
// = humps =
// =========
// version 0.5.1
// Underscore-to-camelCase converter (and vice versa)
// for strings and object keys

// humps is copyright © 2014 Dom Christie
// Released under the MIT license.


;(function(global) {

  var _processKeys = function(convert, obj, separator) {
    if(!_isObject(obj) || _isDate(obj) || _isRegExp(obj)) {
      return obj;
    }

    var output,
        i = 0,
        l = 0;

    if(_isArray(obj)) {
      output = [];
      for(l=obj.length; i<l; i++) {
        output.push(_processKeys(convert, obj[i], separator));
      }
    }
    else {
      output = {};
      for(var key in obj) {
        if(obj.hasOwnProperty(key)) {
          output[convert(key, separator)] = _processKeys(convert, obj[key], separator);
        }
      }
    }
    return output;
  };

  // String conversion methods

  var separateWords = function(string, separator) {
    if (separator === undefined) {
      separator = '_';
    }
    return string.replace(/([a-z])([A-Z0-9])/g, '$1'+ separator +'$2');
  };

  var camelize = function(string) {
    string = string.replace(/[\-_\s]+(.)?/g, function(match, chr) {
      return chr ? chr.toUpperCase() : '';
    });
    // Ensure 1st char is always lowercase
    return string.replace(/^([A-Z])/, function(match, chr) {
      return chr ? chr.toLowerCase() : '';
    });
  };

  var pascalize = function(string) {
    return camelize(string).replace(/^([a-z])/, function(match, chr) {
      return chr ? chr.toUpperCase() : '';
    });
  };

  var decamelize = function(string, separator) {
    return separateWords(string, separator).toLowerCase();
  };

  // Utilities
  // Taken from Underscore.js

  var toString = Object.prototype.toString;

  var _isObject = function(obj) {
    return obj === Object(obj);
  };
  var _isArray = function(obj) {
    return toString.call(obj) == '[object Array]';
  };
  var _isDate = function(obj) {
    return toString.call(obj) == '[object Date]';
  };
  var _isRegExp = function(obj) {
    return toString.call(obj) == '[object RegExp]';
  };

  var humps = {
    camelize: camelize,
    decamelize: decamelize,
    pascalize: pascalize,
    depascalize: decamelize,
    camelizeKeys: function(object) {
      return _processKeys(camelize, object);
    },
    decamelizeKeys: function(object, separator) {
      return _processKeys(decamelize, object, separator);
    },
    pascalizeKeys: function(object) {
      return _processKeys(pascalize, object);
    },
    depascalizeKeys: function () {
      return this.decamelizeKeys.apply(this, arguments);
    }
  };

  if (typeof define === 'function' && define.amd) {
    define(humps);
  } else if (typeof module !== 'undefined' && module.exports) {
    module.exports = humps;
  } else {
    global.humps = humps;
  }

})(this);


// Handlebars helpers
Handlebars.registerHelper("math", function(lvalue, operator, rvalue, options) {
    lvalue = parseFloat(lvalue);
    rvalue = parseFloat(rvalue);
        
    return {
        "+": lvalue + rvalue,
        "-": lvalue - rvalue,
        "*": lvalue * rvalue,
        "/": lvalue / rvalue,
        "%": lvalue % rvalue
    }[operator];
});


Handlebars.registerHelper("count", function(lenghthelpvervalue) {
    return _.size(lenghthelpvervalue);
});

Handlebars.registerHelper("str_limit", function(str, limit){
	if(typeof str !== "string") return str;
	if(typeof limit !== "number") limit = 20;
	return str.slice(0, limit) + '...';
});

function export_table_to_excel(id, filename) {
	var theTable = document.getElementById(id);
	if(typeof filename === "undefined") filename = new Date();
	$.getScript( "/js/excel-combined.js", function( data, textStatus, jqxhr ) {
		
		var tablegen = generateArray(theTable);
		var ranges = tablegen[1];
		
		/* original data */
		var data = tablegen[0]; 
		var ws_name = "Sheet 1";
		
		var wb = new Workbook(), ws = sheet_from_array_of_arrays(data);
		 
		/* add ranges to worksheet */
		ws['!merges'] = ranges;
		
		/* add worksheet to workbook */
		wb.SheetNames.push(ws_name);
		wb.Sheets[ws_name] = ws;
		var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:false, type: 'binary'});
		
		
		// alert(wbout);
		var blob = new Blob([s2ab(wbout)],{type:"application/octet-stream;charset=utf-8"});
		setTimeout(function() { saveAs(blob, filename + ".xlsx"); }, 100);
	});	

}


