	<script src="/quicksand_js/jquery-migrate-1.4.1.min.js"></script>	
	<script src="/quicksand_js/jquery.browser.min.js"></script>	


<link rel="stylesheet" type="text/css" href="/quicksand_js/css/concrete/css/ccm.base.css">

<script type="text/javascript" src="/quicksand_js/css/concrete/js/ccm.base.js"></script>

<link rel="stylesheet" type="text/css" href="/quicksand_js/css/concrete/css/ccm.app.css">	
<link rel="stylesheet" type="text/css" href="/quicksand_js/css/concrete/css/jquery.ui.css" />

	<style>
		html{
			margin-top: 50px !important;
		}
		.header-fixed .header.header-sticky{
			top: 50px;
		}
		
		.edit-span-wrapper:hover{
			outline: dotted 2px red;
		}
		.ui-dialog{
			left: 0 !important;
			right: 0 !important;
			top: 0 !important;
			bottom: 0 !important;
			width: 90% !important;
			height: 90% !important;
			margin: auto;
		}
		#ccm-dialog-content1{
			height: calc(100% - 54px) !important;
			width: 100% !important;
		}
		.EDITOR--btn-block{
			display: block;
			clear: both;
		}	
		.ace{
			width: 100%;
			min-height: 400px;
		}	
		.ccm-ui .popover.below .arrow{
			top: -10px;
		}	
		.blockeditwrapper{
			min-height: 40px;
		}			
	</style>
	
	<script type="text/javascript">
	var CCM_DISPATCHER_FILENAME = '/index.php';
	var CCM_CID = 12345;
	var CCM_EDIT_MODE = {if edit_mode()}true{else}false{/if};
	var CCM_ARRANGE_MODE = false;
	var CCM_IMAGE_PATH = "/images";
	var CCM_TOOLS_PATH = "/index.php/tools/required";
	var CCM_BASE_URL = "{url('/')}";
	var CCM_REL = "";
	var CCM_SECURITY_TOKEN = '{csrf_token()}';
	var _token = '{csrf_token()}';
	var projectPath = '';
	
// 	alert(_token);
	
	
	</script>

<script src="/quicksand_js/dropzone.js"></script>
<script src="/quicksand_js/ckeditor/ckeditor.js"></script>
<script src="/quicksand_js/ace/src-noconflict/ace.js"></script>
<script src="/quicksand_js/ace/src-noconflict/ext-language_tools.js"></script>
<script src="/quicksand_js/ace/src-noconflict/ext-emmet.js"></script>
<script src="/quicksand_js/ckeditor/adapters/jquery.js"></script>    
<script src="/quicksand_js/emmet-core/emmet.js"></script>

<script src="/quicksand_js/jquery.ui.js"></script>

<script src="/quicksand_js/ccm.app.js"></script>


{include 's3:parts/edit_popover.tpl'}

<!-- Modal -->
<div class="modal Xfade" id="editor_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editor_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">Edit Block</h4>
			</div>
			<div class="modal-body">
			<p>:)</p>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" onclick="$('#editor_modal').find('form').submit()" class="btn btn-primary">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
$(function() {
	var item, sbitem, btn, btn1, btn2, csrf='{csrf_token()}';

		ccm_statusBar.activate();		
		$(".launch-tooltip").tooltip();
		ccm_activateToolbar();
});		
$.curCSS = function (element, attrib, val) {
    $(element).css(attrib, val);
};

loadMyDialog = function() {

}
// gather editor styles
var $themestyles = $('head').find('[rel="stylesheet"]').filter('[href*="/themefile/"]');
var stylesheets = $themestyles.map(function(){
	return $(this).attr('href');
});
// alert('sdf');


var save_successful = false;
var fns = {};
fns['EDITOR--edit_button'] = function($this){

	$modalBody = $('#editor_modal').find('.modal-body');
	console.log($this.data());
	var bID = $this.data('bid');
	var $actualBlockElem = $this;
	$.fn.dialog.open({
		title: 'Edit',
		href: '/dashboard/block/' + bID + '/true',
		modal: true,
		escapeClose: false,
		resizable: false,
		onClose: function() {
			destroy_ckeditors();
			if(save_successful)
				$actualBlockElem.load('/dashboard/block/' + bID);
		},
		onOpen: function(){
			save_successful = false;
			
			init_ckeditors(function(){
				setTimeout(function(){ $(window).scrollTop(0); }, 1000);
			});

			
		},

	});		
}

fns['EDITOR--save_button'] = function($this){
	$.post('/dashboard/block/' + $this.data('bid'), serializeArray2Object($this, { _token: _token, _method: 'put' }),function(data){

		if(data.ok) {
			save_successful = true;
			alert('Saved');
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
	$.post('/dashboard/area/' + $this.data('aid'), serializeArray2Object($this, { _token: _token, _method: 'put' }),function(data){
console.log(data);
		if(data.ok) {
			save_successful = true;
			alert('Block Added');
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
	
	$.post('/dashboard/block/' + $this.data('bid'), serializeArray2Object($this, { _token: _token, _method: 'delete' }),function(data){

		if(data.ok) {
			save_successful = true;
			alert('Deleted');
			window.location.reload();
		}
		else {
			save_successful = false;
			alert('ERROR');
		}
		console.log(data);
	});
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

$(document).on('click', '.formsubmitter', function(){
	$(this).parent().prev('form').submit();
});

$(document).on('click Xeditoraction submit', '[data-editoraction]', function(e){
// 	e.stopPropagation();
// 	console.log(e);
	if($(this).data('target')) e.target = $(this).data('target');
	e.preventDefault();
	$this = $(e.target);
	console.log($this);
	var actionkey = $(this).data('editoraction');
	if(actionkey in fns)
		fns[actionkey]($this);
});

var edit_popover_template = Handlebars.compile($('#edit_popover_template').html());

$(document).on('click', '.blockeditwrapper', function(e){
	console.log(e);
	e.preventDefault();
	e.stopPropagation();
	$('#edit_popover').remove();
	$('body').append(edit_popover_template({
		bid: $(this).data('bid'),
		top: e.pageY + 5,
		left: e.pageX - 100
	}));
});

// target is DOM node, not a jQ object
function editoraction(target){
	$.event.trigger({
		type: "editoraction",
		target: target,
		time: new Date()
	});
}

$('[data-toggle="popover"]').popover({
		html: true, 
		placement: 'auto top',
		content: function(){
			return $(this).prev('.block_add_content').html()
		}
	}
);

/*

$(document).on('shown.bs.modal', function(e){
	console.log(e.target);
	init_ckeditors();
});

$(document).on('hide.bs.modal', function(e){
	if(!confirm('saved?')) return false;
});
	
$(document).on('hidden.bs.modal', function(e){
	destroy_ckeditors();
});
*/
	
	
$(document).on('change', '#versionselector', function(e){
	if(!$('#previewiframe').length){
		$(this).after('<iframe id="previewiframe" width="100%" height="800px" frameborder="0" />');
	}
	$('#previewiframe').attr('src', $(this).data('path') + '?page_version=' + $(this).val());
});
	
if($('#messagearea .alert-success').length)
	setTimeout(function(){
		$('#messagearea .alert-success').fadeOut();
	}, 1500);
	
		
var aceeditors = {};
var acepreviewers = {};
var editortextareas = {};
// init editor areas
function init_ckeditors(callback){
	if( typeof CKEDITOR !== "undefined"){
		
		CKEDITOR.on('instanceCreated', function (e) {
		    e.editor.on('fileUploadRequest', function (evt) {
			    var xhr = evt.data.fileLoader.xhr;
			    xhr.setRequestHeader( 'Cache-Control', 'no-cache' );
			    xhr.setRequestHeader( 'X-CSRF-TOKEN', _token );
// 					    xhr.withCredentials = true;
		    });
		});
	}

	$('.editor').each(function(e){
		if($(this).hasClass('advanced-only')){
			makeAce($(this));
		}
		else
			$(this).ckeditor({
			     contentsCss: stylesheets.toArray(),
			     stylesheetParser_skipSelectors: /(^body\.|^svg\.)/i,
// 			     stylesheetParser_validSelectors: /(^span\.text)/i,
			     emailProtection: 'encode',
			});
	});	
	if(callback)
		callback();		
}
		
function destroy_ckeditors(callback){
	for(ckid in CKEDITOR.instances){
		var ckinstance = CKEDITOR.instances[ckid];	
		ckinstance.destroy();
	}
	if(callback) callback();
}
	
	
function convert_to_ace(){
	var fieldset = $(this).parents('.form-control, fieldset');
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


$(document).on('click', '.advanced-editor-show', function (event) {
	convert_to_ace();
});

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
	    bindKey: { win: 'Ctrl-[',  mac: 'Command-[' },
	    exec: function(editor) {
	        editor.blockOutdent();
	    }
	});		    
	thisace.commands.addCommand({
	    name: 'indent',
	    bindKey: { win: 'Ctrl-]',  mac: 'Command-]' },
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
	</script>


<!-- the multi-modal -->
<div class="modal fade" id="multi_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"> &nbsp;</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="
            position: absolute;
		    top: 10px;
		    right: 10px;
    "><span aria-hidden="true"><i class="fa fa-close"></i></span></button>        
      </div>
      <div class="modal-body">
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>