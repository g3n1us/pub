var notsaved = false;



$('[data-ajaxload]').each(function(){
	$(this).load($(this).data('ajaxload'));
});
	
var sortcontainer = $('.ccm_area_wrapper')[0];
var sortcontainers = {};
$('.ccm_area_wrapper').each(function(){
	sortcontainers[$(this).attr('id')] = Sortable.create($(this)[0], {
		group: "areas",
		// group: { name: "areas", pull: [true, false, clone], put: [true, false, array] },
		animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		//handle: ".tile__title", // Restricts sort start click/touch to the specified element
		draggable: ".blockeditwrapper", // Specifies which items inside the element should be sortable
		onSort: function (evt/**Event*/){
			var item = evt.item; // the current dragged HTMLElement
			fns['EDITOR--move_block']($(evt.item));
		},
		onStart: function (evt){
			dropuploadok = false;
		}
	});
});
// if(sortcontainer) {
// 	var areablocksortsort = Sortable.create(sortcontainer, {
// 	  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
// 	  //handle: ".tile__title", // Restricts sort start click/touch to the specified element
// 	  draggable: ".blockeditwrapper", // Specifies which items inside the element should be sortable
// 	  onUpdate: function (evt/**Event*/){
// 	     var item = evt.item; // the current dragged HTMLElement
// 	     fns['EDITOR--move_block']($(evt.item));
// 	  }
// 	});		
// }
	
//$(function() {

/*
if(article)
	var app = new Vue({
	  el: '#app',
	  data: article,
	})	
*/

		//ccm_statusBar.activate();		
$('.launch-tooltip, [rel="tooltip"], [data-toggle="tooltip"]').tooltip({
	placement: function(tt, el){
		return el.offsetTop < 150 ? 'bottom' : 'top';
	}
});
		//ccm_activateToolbar();
//});		
$.curCSS = function (element, attrib, val) {
    $(element).css(attrib, val);
};

loadMyDialog = function() {

}
// console.log(stylesheets);
// alert('sdf');


var save_successful = false;
var fns = {};
fns['EDITOR--edit_button'] = function($this){
	$modal = $('#editor_modal');
	$modalBody = $modal.find('.modal-body');
	console.log($this.data());
	var bID = $this.data('bid');
	var $actualBlockElem = $this;
	$modal.modal('show');
	$modalBody.load('/block/' + bID + '/edit', function(){
		init_ckeditors();
	});
}

fns['EDITOR--save_button'] = function($this){
	$.post('/block/' + $this.data('bid'), serializeArray2Object($this, {_token: _token, _method: 'put'}),function(data){

		if(data.ok) {
			save_successful = true;
			// alert('Saved');
			window.location.reload();
		}
		else {
			save_successful = false;
			alert('ERROR');
		}
		console.log(data);
	});
}
fns['EDITOR--add_block'] = function($this){
	$.post('/dashboard/area/' + $this.data('aid'), serializeArray2Object($this, {_token: _token, _method: 'put'}),function(data){
		console.log(data);
		if(data.ok) {
			save_successful = true;
			// alert('Block Added');
			window.location.reload();
		}
		else {
			save_successful = false;
			alert('ERROR');
		}
		console.log(data);
	});
}
fns['EDITOR--delete_block'] = function($this){
	if(!confirm("Are you sure?")) return false;
	
	$.post('/block/' + $this.data('bid'), serializeArray2Object($this, {_token: _token, _method: 'delete'}),function(data){

		if(data.ok) {
			save_successful = true;
			// alert('Deleted');
			window.location.reload();
		}
		else {
			save_successful = false;
			alert('ERROR');
		}
		console.log(data);
	});
}

var array_move = function (arr, old_index, new_index) {
    while (old_index < 0) {
        old_index += arr.length;
    }
    while (new_index < 0) {
        new_index += arr.length;
    }
    if (new_index >= arr.length) {
        var k = new_index - arr.length;
        while ((k--) + 1) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr; // for testing purposes
};

fns['EDITOR--move_block'] = function($this){
	if($this.is('.blockeditwrapper')) var $thisblock = $this;
	else var $thisblock = $('#blockID-' + $this.data('bid'));
	console.log($thisblock);
	
	var $area = $thisblock.parents('.ccm_area_wrapper');
	var idsarray = $area.find('.blockeditwrapper').map(function(k,v){
		return $(v).data('bid');
	});
	idsarray = idsarray.toArray();
	console.log(idsarray)
	var current_pos = idsarray.indexOf($this.data('bid'));
	var reloadpage = true;
	if($this.data('direction') == 'up'){
		var newpos = current_pos - 1;
		array_move(idsarray, current_pos, newpos);
	}
	else if($this.data('direction') == 'down'){
		var newpos = current_pos + 1;
		array_move(idsarray, current_pos, newpos);		
	}
	else reloadpage = false;
	var area_blocks = getAreaBlocks();
	$.post('/dashboard/area/' + $area.data('aid'), {area_blocks: area_blocks, _token: _token}, function(data){
		console.log(data);
		if(data.ok) {
			save_successful = true;
			if(reloadpage)
				window.location.reload();
		}
		else {
			save_successful = false;
			alert('ERROR');
		}
		console.log(data);
	});	
	
}

function getAreaBlocks(){
	var areas = {};
	$('.ccm_area_wrapper').each(function(){
		var blocks = [];
		$(this).find('.blockeditwrapper').each(function(){
			blocks.push($(this).data('bid'));
		});
		areas[$(this).data('aid')] = blocks;
	});
	return areas;
}

function serializeArray2Object($form, appendedObj){
	var returnVal = {};
	$form.serializeArray().forEach(function(obj){
		returnVal[obj.name] = obj.value; 
	});
	if(appendedObj){
		for(k in appendedObj) returnVal[k] = appendedObj[k]; 
	}
	if($form.data('formdata')){
		var jsonExtra = $form.data('formdata');
		
		for(k in jsonExtra) returnVal[k] = jsonExtra[k]; 
	}
	return returnVal;
}


// var edit_popover_template = Handlebars.compile($('#edit_popover_template').html());


// target is DOM node, not a jQ object
function editoraction(target){
	$.event.trigger({
		type: "editoraction",
		target: target,
		time: new Date()
	});
}

	
if($('#messagearea .alert-success').length)
	setTimeout(function(){
		$('#messagearea .alert-success').fadeOut();
	}, 1500);
	
		
var aceeditors = {};
var acepreviewers = {};
var editortextareas = {};
var Editor;
var editor_has_changed = false;
// init editor areas
if( typeof CKEDITOR !== "undefined"){
	
	CKEDITOR.on('instanceCreated', function (e) {
		var thiseditor = e.editor;
		replace_script_tags(thiseditor);

		thiseditor.on('blur', function(evt) {   
			replace_script_tags(thiseditor);
			if(editor_has_changed)
				makeContentSuggestions();
//		    evt.data['html'] = '<!--class="Mso"-->'+evt.data['html'];
		});
		
		thiseditor.on('paste', function(evt) {  
			replace_script_tags(thiseditor);
//		    evt.data['html'] = '<!--class="Mso"-->'+evt.data['html'];
		});
		
		thiseditor.on('mode', function () {
			replace_script_tags(thiseditor);
		});
		
	    thiseditor.on('fileUploadRequest', function (evt) {
		    var xhr = evt.data.fileLoader.xhr;
		    xhr.setRequestHeader( 'Cache-Control', 'no-cache' );
		    xhr.setRequestHeader( 'X-CSRF-TOKEN', _token );
	    });
	    
	    thiseditor.on('change', function(e){
		    editor_has_changed = true;
		    notsaved = true;
	    });
		
	});
}

function replace_script_tags(editor){
	var current_markup = editor.getData();
	if(current_markup.search(/<\/script>/) !== -1) {	
	    current_markup = current_markup.replace(/<script(.*?)>/gi, '<div data-replacement_function="scriptreplacer"$1>');
	    current_markup = current_markup.replace(/<\/script>/gi, '</div>');
	    editor.setData(current_markup);
	}
	else return current_markup;
}

CKEDITOR.plugins.addExternal( 'mdc_ad', '/app/2016/js/ckeditor_plugins/mdc_ad/' );
CKEDITOR.plugins.addExternal( 'g3n1us_component', '/app/2016/js/ckeditor_plugins/g3n1us_component/' );
CKEDITOR.plugins.addExternal( 'article_embed', '/app/2016/js/ckeditor_plugins/article_embed/' );
CKEDITOR.config.embed_provider = '//iframe.ly/api/oembed?url={url}&callback={callback}&api_key=2af12597b56249147b3e43';


function init_ckeditors(){
	$('.editor').each(function(e){
		if($(this).hasClass('advanced-only')){
			makeAce($(this));
		}
		else
			$(this).ckeditor({
				// contentsCss: stylesheets.toArray(),
				contentsCss: ['https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700', '/app/2016/ckeditor.css'],
				stylesheetParser_skipSelectors: /(^body\.|^svg\.)/i,
				// 			     stylesheetParser_validSelectors: /(^span\.text)/i,
				emailProtection: 'encode',
				uploadUrl : "/filemanager?show_response=1&article_id="+article_id,
				imageUploadUrl : "/filemanager?show_response=1&article_id="+article_id,
// 				imageUploadUrl : "/dashboard/upload/1/"+article_id,
				filebrowserBrowseUrl: '/filemanager',
				templates_files: [ '/app/2016/js/ckeditor_templates.js' ],
				templates_replaceContent: false,
				extraPlugins: 'mdc_ad,g3n1us_component,article_embed',
				// embed_provider: '//iframe.ly/api/oembed?api_key=2af12597b56249147b3e43'
				keystrokes: [
					[ CKEDITOR.CTRL + 83 /*S*/, 'save' ],
				]
			});
	});	
	var ck_instances = [];		
	for(ckid in CKEDITOR.instances){
		var ckinstance = CKEDITOR.instances[ckid];	
		ck_instances.push(ckinstance);
		ckinstance.on('change', function(e){
			editor_has_changed = true;
			// console.log(e.editor.getData());
		});
		ckinstance.on('focus', function(e){
			dropuploadok = false;
			if(typeof relatedfilessort !== "undefined")
				relatedfilessort.option('disabled', true);
		});
		ckinstance.on( 'paste', function( evt ) {
			console.log(evt);
//			evt.data.dataValue = '<h1>Howdy</h1>';
 			var custom_html = evt.data.dataTransfer.getData( 'custom_html' );
 			var text_content = evt.data.dataTransfer.getData('text');
 			if(is_url(text_content)){
	 			var parsed_url = parse_url(text_content);
	 			if(parsed_url.hostname == window.location.hostname){
		 			evt.data.dataValue = '<a data-oembed_url="'+text_content+'">'+text_content+'</a>';
		 			return;
	 			}
 			}
 			if ( !custom_html ) {
 				return;
 			}
 			else evt.data.dataValue = custom_html;
 			
// 
// 			evt.data.dataValue =
// 				'<span class="h-card">' +
// 					'<a href="mailto:' + contact.email + '" class="p-name u-email">' + contact.name + '</a>' +
// 					' ' +
// 					'<span class="p-tel">' + contact.tel + '</span>' +
// 				'</span>';
		});
		
	}
	if(ck_instances.length){
		Editor = ck_instances[0];
		Editor.on( 'focus', function(e) {
		    dropuploadok = false;
		} );				
	}
	
}




var tests = {
	ads_inserted: {
		test: function($content){
			return $content.find('.mdc--ad').length > 0;
		},
		message: "You don't seem to have any ads placed inside your article. That's ok, but as a heads up, they will be placed automatically otherwise.",
		seen: false,
	},
	has_embeds: {
		test: function($content){
			console.log($content.find('[data-oembed-url]').length);
			return $content.find('[data-oembed-url]').length > 0;
		},
		message: "You don't seem to have any embeds placed inside your article. Did you know you can just paste the URL? Give it a try.",
		seen: false,
	},
};


function makeContentSuggestions(){
	editor_has_changed = false;
	if(!$('#editor_messages').is(':empty')) return;
	var $content = $('<div>'+Editor.getData()+'</div>');
	var $message = $('<div class="alert" />');
	var messages = [];
	for(var i in tests){
		if(tests[i].test($content) === false) {
			messages.push(tests[i].message);
			tests[i].seen = true;
		}
	}
	if(messages.length){
		$message.addClass('alert-info').html('• ' + messages.join('<br>• '));
		$message.prepend('<a class="close" data-dismiss="alert">&times;</a>');
		$('#editor_messages').html($message);		
	}
}

/*
var to = setInterval(function(){
	if(editor_has_changed)
		makeContentSuggestions();
//	console.log(CKEDITOR.instances);
	//alert(iteration);
}, 3000);
*/


function destroy_ckeditors(callback){
	for(ckid in CKEDITOR.instances){
		var ckinstance = CKEDITOR.instances[ckid];	
		ckinstance.destroy();
	}
	if(callback) callback();
}
	
	
function convert_to_ace(){
	var fieldset = $(this).parents('.form-group, fieldset');
	var ckid = fieldset.find('label').attr('for');	

	$('#' + ckid + '_advanced').val(1);
	if(ckid in CKEDITOR.instances){
		var ckinstance = CKEDITOR.instances[ckid];	
		ckinstance.destroy();
		editortextareas[ckid] = fieldset.find('textarea');
		makeAce(editortextareas[ckid]);	
	}
	else{
		$('#' + ckid + "_advanced").val(0);
		if(confirm("CAUTION!\n\nSome HTML formatting may be lost when converted for use with the WYSIWYG editor.\n\nClick OK to save the page and refresh the editor.")) $('form.keyshortcut').submit();
	}
	
}	



function makeAce(jqobj){
	if(jqobj.length) jqobj = jqobj.first();
	if(!jqobj.is('textarea')){
		console.error('You must specify a textarea');
		return;
	}
	var ogid = jqobj.attr('id');
	if(ogid === "undefined"){
		ogid = 'acetextarea-' + Math.floor((Math.random()*100)+1);
		jqobj.attr('id', ogid);
	}
	id = "ace-" + ogid;
	var acediv = $('<div />');
	acediv.addClass('ace');
	if(typeof jqobj.attr('rows') !== "undefined") acediv.css('min-height', jqobj.attr('rows') * 100);
	acediv.attr('id', id);
	jqobj.before(acediv);
	ace.require("ace/ext/language_tools");
	ace.require("ace/ext/emmet");
	var thisace = ace.edit(id);
	thisace.setOptions({
	    enableBasicAutocompletion: true,
// 			    enableSnippets: true,
        enableLiveAutocompletion: true,
        enableEmmet: true,
	});			
    thisace.setTheme("ace/theme/tomorrow_night_eighties");
    var mode = jqobj.data('editor-mode') == undefined ? "html" : jqobj.data('editor-mode');
    thisace.getSession().setMode("ace/mode/" + mode);
    
	thisace.commands.addCommand({
	    name: 'outdent',
	    bindKey: {win: 'Ctrl-[',  mac: 'Command-['},
	    exec: function(editor) {
	        editor.blockOutdent();
	    }
	});		    
	thisace.commands.addCommand({
	    name: 'indent',
	    bindKey: {win: 'Ctrl-]',  mac: 'Command-]'},
	    exec: function(editor) {
	        editor.blockIndent();
	    }
	});		    
    
    thisace.setValue(jqobj.val());
    var thispreview = $('<div />');
    if(mode === "html"){
	    // make preview div
		thispreview.addClass('advanced-preview');
		thispreview.attr('id', 'ace-preview-' + id);			
		thispreview.html(thisace.getValue());
		jqobj.before(thispreview);
	    
    }
	
	jqobj.hide();
    
	thisace.getSession().on('change', function(e) {
	    thispreview.html(thisace.getValue());
	    jqobj.val(thisace.getValue());
	});		
	$('[for="' + ogid + '"] .advanced-editor-show').text('WYSIWYG Editor');
}

dropuploadok = true;
Dropzone.autoDiscover = false;
var uploadcovershowing = false;
$('body, .uploadcover').on('dragenter', function(e){
	console.log('dragenter called');
	uploadcovershowing = true;
	if(dropuploadok)
		$('.uploadcover').addClass('showing');
});
$(window).on('dragleave', function(e){
	console.log('dragleave called');
		uploadcovershowing = false;	
	setTimeout(function(){
		if(!uploadcovershowing){
			$('.uploadcover').removeClass('showing');
		}
	}, 1000);
	
});

var dropzone_configuration = {
	previewsContainer: "#dz-previews",
	// You probably don't want the whole body
	// to be clickable to select files
	clickable: false,
	url: "/filemanager",
	paramName: "upload",
	previewsContainer: "#dz-previews",
	// clickable: "#ccm-upload-files",
	maxFilesize: 10,
	acceptedFiles: "image/*,application/pdf,.svg,.pdf",
	sending: function(file, xhr, formData) {
		formData.append("_token", csrf);
		formData.append("article_id", article_id);
	},
	complete: function(){
		if($('.related-files-outer').length) $.getJSON('/ajax/article/'+article_id+'?pluck=files', function(data){
			$('.related-files-outer').html('loading...');
			setTimeout(function(){
				$('.related-files-outer').html(handlebars_templates.related_file({files: data, article: article}).trim());
	//			$('.collapse').collapse();
			}, 3000);
		});
	} 
};
var clickable_dropzone_configuration = dropzone_configuration;
clickable_dropzone_configuration.clickable = true;

if(document.getElementById('filebrowseruploadtab'))
	var FileBrowserDropzone = new Dropzone('#filebrowseruploadtab', clickable_dropzone_configuration);

var BodyDropzone = new Dropzone($(".uploadcover").get(0), dropzone_configuration);

if($('.related-files-outer').length){
	$.getJSON('/ajax/article/'+article_id+'?pluck=files', function(data){
		// console.log(data);
		$('.related-files-outer').html(handlebars_templates.related_file({files: data, article: article}).trim());
		// $('.collapse').collapse();		
	});	
}


$('#dz-previews.not-filled').one('mouseenter', function(){
	if(!$(this).find('.fa-close.close').length)
		$(this).prepend('<i class="fa fa-close fa-3x close" style="color:white; opacity: .95"></i>');
	$(this).removeClass('not-filled');
});


/*
if($('#article_versions').length){
	$.getJSON('/article_versions/'+article_id, function(data){
		data.forEach(function(v,k){
			$('#article_versions').append('<option value="'+v.VersionId+'">Version '+v.index+' - '+v.date_diff+' - '+v.LastModified.date+'</option>');
		});
	});
}
*/

$('#editor_modal').modal({
	backdrop: 'static',
	keyboard: false,
	show: false,
	focus: false, // This is for a fix to Ckeditor inside the modal
});

$('[data-toggle="modal"]').each(function(){
	var selector = $(this).data('target') || $(this).attr('href');
	$(selector).modal({show:false});
});




$('.item a').not('.name').attr('target', '_blank');
var tab = 'files';

if(window.location.hash) {
	getPaged(window.location.hash.slice(1));
}
	
else{
	getPaged();
}
	



function getPaged(linkurl){
	if(!tab) return;
	if(!linkurl) linkurl = '/filemanager/tab/' + tab + '?page=1';
	$.getJSON(linkurl, function(files){
		console.log(files);
		$('#' + tab).html(handlebars_templates[tab + '_template'](files.data));
		$('#' + tab + '_nav').html(files.links);
// 					$('[href="#'+tab+'tab"]').tab('show');
		$('.pagination').find('li').addClass('page-item');
		$('.pagination').find('a, span').addClass('page-link');	
		$('#' + tab).addClass('loaded');	
		init_daraggable_imgs();			
	});
}

function init_daraggable_imgs(){
/*
	if(document.getElementById('cke_content[body]'))
		Sortable.create(document.getElementById('cke_content[body]'), { group: "filemanager", draggable: ".card", });
		
	if($('.cke_contents iframe').length)
		Sortable.create($('.cke_contents iframe')[0], { 
			group: "filemanager", 
			draggable: ".card img", 
			onStart: function(evt){
				alert('sdfsdf');
			}, 
			
		});
*/
	
	var relatedfilesupdatecontainer = $('#filemanager_blade_outer #files .card-columns')[0];
	if(relatedfilesupdatecontainer) {
		window.dropuploadok = false;
		window.relatedfilessort = Sortable.create(relatedfilesupdatecontainer, {
			animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
			// handle: ".card-block", // Restricts sort start click/touch to the specified element
			draggable: ".card", // Specifies which items inside the element should be sortable
			// 					  draggable: ".card-img-top", // Specifies which items inside the element should be sortable
			group: { name: "filemanager",pull: 'clone', put: false },
			onStart: function(evt){	
				window.dropuploadok = false;
			},
			setData: function (dataTransfer, dragEl) {
			    dataTransfer.setData("custom_html", $(dragEl).find('img')[0].outerHTML); 
				dataTransfer.setData( 'text/html', $(dragEl).find('img')[0].outerHTML );					        
			},
			  
			onEnd: function(evt){	
			  console.log($(evt.item).parents('.related-files-outer'));
			  console.log(evt.item);
			  if($(evt.item).parents('.related-files-outer').length){
				  var $item = $(evt.item).find('img');
				  $.post('/filesave/'+$item.data('id')+'/'+article_id, { _token: _token}, function(data){
					$.getJSON('/ajax/article/'+article_id+'?pluck=files', function(data){
						$('.related-files-outer').html(handlebars_templates.related_file({ files: data, article: article}).trim());
					});	
				  });
			  }
			  console.log(evt);
			},
			onUpdate: function (evt){
			  console.log(evt);
			}
		});	
		console.log(relatedfilessort);	
	}
	var relatedfilesouter = $('.related-files-outer')[0];	
	if(relatedfilesouter) {
		var relatedfilessort2 = Sortable.create(relatedfilesouter, {
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  //handle: ".tile__title", // Restricts sort start click/touch to the specified element
		  draggable: ".card", // Specifies which items inside the element should be sortable
		  group: { name: "filemanager",pull: true, put: true},
// 					  group: "filemanager",
		  onEnd: function(evt){
			  console.log($(evt.item));
			  if(!$(evt.item).parents('.related-files-outer').length){
				  alert('take me out');
			  }
		  },
		  onUpdate: function (evt){
			  console.log(evt);
	//	     var item = evt.item; // the current dragged HTMLElement
	//	     fns['EDITOR--move_block']($(evt.item));
		  }
		});	
		console.log(relatedfilessort2);	
	}
	
}
	

function tokenize(){
	$('.tokenable_input').not('[type="hidden"]').each(function(){
		$(this).attr('type', 'hidden').wrap('<div class=\'input_token h5 mb-1\' style=\'display: inline-block\'><span class=\'tag tag-primary\'><a class=\'close\'>&times;</a><span class=\'input_token_text\'>'+$(this).val()+'</span></span></div>');
	});	
}


// Helper function to get parameters from the query string.
function getUrlParam(paramName){
	var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	var match = window.location.search.match(reParam) ;	
	return (match && match.length > 1) ? match[1] : '' ;
}


$('[class*="EDITOR"][data-toggle="popover"]').popover({
	html: true, 
	placement: 'top',
	content: function(){
		return $(this).prev('.block_add_content').html()
	},
	container: '#editorouter',
	trigger: 'focus',
});


$('[data-toggle="popover"]').not('[class*="EDITOR"]').popover({
	html: true, 
//		trigger: 'focus',
});
