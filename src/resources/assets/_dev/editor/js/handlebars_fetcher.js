		var handlebars_templates = {};
		var raw_handlebars_templates = [];
		$('script[type*="template"]').each(function(){
			var thisid = $(this).attr('id');
			thisid = thisid.replace(/-/g, "_");
			var source  = $(this).html();
			if(typeof $(this).data('default') === "undefined") var usedata = false;
			else usedata = $(this).data('default');
			raw_handlebars_templates.push({ "id": thisid, "source": source, "datasource": usedata});
			handlebars_templates[thisid] = Handlebars.compile(source);
		});
		
