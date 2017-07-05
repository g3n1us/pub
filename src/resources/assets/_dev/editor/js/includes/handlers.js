//! before unload

$(window).on('beforeunload', function(e){
	for(var i in application_windows) application_windows[i].close();

	if(notsaved) return "You have unsaved changes.\r\nAre you sure you want to leave?";
});

$(document).on('dragstart', '[data-draggablecontent]', function(e){
// 	e.preventDefault();
	var t = $(this).text().trim();
	console.log(t);
	
	e.originalEvent.dataTransfer.setData('text/plain', t);
});

//! document.ready
$(document).ready(function(){
	init_ckeditors();
	$('div[data-replacement_function="scriptreplacer"]').each(function(){
		var attributes = $(this)[0].attributes;
		if(!attributes) return;
		var $script = $(document.createElement('script'));
		$.each(attributes, function() {
		    $script.attr(this.name, this.value);
		});		
		$(this).before($script);
		$(this).before($script).remove();
	});

});

$(document).ready(function(){
	tokenize();
});

$(document).on('click', '.redirectToNoQueryString', function(e){
	e.preventDefault();
	window.location.assign(window.location.pathname);
});

$(document).on('click', '[data-toggle="popover"]', function(e){ e.preventDefault();});

//! Frontend Editing

$(document).on('click', '.blockeditwrapper', function(e){
	e.preventDefault();
	e.stopPropagation();
	$('#edit_popover').remove();
	$(this).addClass('blockeditwrapper_focused');
	if($(this).attr('data-tmpid')){
		var tmpid = $(this).data('tmpid');
	}
	else{
		var tmpid = 'randid'+Math.floor((Math.random()*10000)+1);
		$(this).attr('data-tmpid', tmpid);
	}
	$('body').append(handlebars_templates.edit_popover_template({
		bid: $(this).data('bid'),
		top: e.pageY + 5,
		left: e.pageX - 100,
		tmpid: tmpid,
	}));
	$('#edit_popover').focus();
});

$(document).on('blur', '#edit_popover', function(){
	$('#edit_popover').remove();
	$('.blockeditwrapper.blockeditwrapper_focused').removeClass('blockeditwrapper_focused');
});




$(document).on('click', '.advanced-editor-show', function (event) {
	convert_to_ace();
});




/*
$(document).on('click', '.formsubmitter', function(){
	$(this).parent().prev('form').submit();
});
*/

/*
$(document).on('click submit', '[data-editoraction]', function(e){
	if($(this).data('target')) e.target = $(this).data('target');
	e.preventDefault();
	$this = $(e.target);
	console.log($this);
	var actionkey = $(this).data('editoraction');
	if(actionkey in fns)
		fns[actionkey]($this);
});
*/




var handleEditorActionTrigger = function(e){
	if($(this).data('target')) e.target = $(this).data('target');
	e.preventDefault();
	$this = $(e.target);
	console.log($this);
	var actionkey = $(this).data('editoraction');
	if(actionkey in fns)
		fns[actionkey]($this);
}


$(document).on('click', 'button[data-editoraction], a[data-editoraction]', handleEditorActionTrigger);

$(document).on('submit', 'form[data-editoraction]', handleEditorActionTrigger);


$(document).on('show.bs.modal', '#multi_modal, #editor_modal', function (event) {
	var $modal = $(this);
	var $button = $(event.relatedTarget); 
	if($modal.is('#editor_modal') && !$button.hasClass('loadcontents')) return;
	var name = $button.data('name');
	var size = $button.data('size');
	$modal.find('.modal-title').text(name);
	$modal.find('.modal-dialog').removeClass('modal-lg modal-sm').addClass(size);	
	if($button.data('modal_handlebars_template'))
		var content = handlebars_templates[$button.data('modal_handlebars_template')]({});
	else
		var content = $button.html();
	$modal.find('.modal-body').html(content);
	$footerbutton = $modal.find('.modal-footer [type="submit"]');
	if($modal.find('.modal-body').find('form').length){
		$footerbutton.attr('form', $modal.find('.modal-body form').attr('id'));
	}
	else
		$footerbutton.attr('form', 'modal_editor_form');
	$modal.find('.hide, .hidden').removeClass('hide hidden');
	lep_loader.outputAllTemplates(null, null, function(){
// 		$modal.data('bs.modal').handleUpdate();
	});
});

$(document).on('shown.bs.modal', '#multi_modal, #editor_modal', function (event) {
	$(this).find('[autofocus]').first().focus();
	tokenize();
});

$(document).on('hidden.bs.modal', '#editor_modal', function (event) {
	destroy_ckeditors();
});

/*
$(document).on('shown.bs.modal', '#editor_modal', function(event){
	var $button = $(event.relatedTarget); 
	if($button.data('modal_handlebars_template'))
		var content = handlebars_templates[$button.data('modal_handlebars_template')]({});
	else
		var content = $button.html();
	var name = $button.data('name');
	var size = $button.data('size');
	var modal = $(this);
	modal.find('.modal-title').text(name);
	modal.find('.modal-dialog').removeClass('modal-lg modal-sm').addClass(size);
	modal.find('.modal-body').html(content);
	modal.find('.hide, .hidden').removeClass('hide hidden');		
	
	init_ckeditors();
});
*/

$(document).on('input', '[data-sluggify]', function(){
	$sluggifytarget = $($(this).data('sluggify'));
	$sluggifyval = str_slug($(this).val());
	$sluggifytarget.val($sluggifyval);
});

//! ckeditor filepicker function
$(document).on('click', '[data-path]', function(e){
	var funcNum = getUrlParam('CKEditorFuncNum');	
	if(funcNum != ""){
		e.preventDefault();
		var fileUrl = $(this).data('path');
		window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
		window.self.close();					
	}
});

$(document).on('click', '[data-tab]', function(e){
	tab = $(this).data('tab');
	if($(this).is('[data-toggle="tab"]'))
		window.location.href = '#';
	if(!$('#' + tab).is('.loaded'))
		getPaged();
});


$(document).on('click', '#filemanager_blade_outer .pagination a', function(e){
	e.preventDefault();
	var linkurl = $(this).attr('href');
	window.location.hash = linkurl;
	getPaged(linkurl);
});


$(document).on('click', '#dz-previews .close', function(){ 
	$(this).parent().html('');	
});
	

//! Article edit page

$(document).on('blur', '.remove_if_empty_on_save', function(e){
	var thisname = $(this).attr('name');
	if($(this).val().length === 0 && $('[name="' + thisname + '"]').length > 1) $(this).remove();
});

$(document).on('change', '#author_selector', function(e){
	var names = $('.selected_author').map(function(){
		return $(this).find('span.input_token_text').text();
	});
	$('[name="author_display"]').val(names.toArray().join(' and '));
});
	
$(document).on('click', '.selected_author .close',function(e){
	$(this).parents('.input_token').remove();
	var names = $('.selected_author').map(function(){
		return $(this).find('span.input_token_text').text();
	});
	$('[name="author_display"]').val(names.toArray().join(' and '));	
});

$(document).on('blur', '.tokenable_input', function(e){
	$(this).attr('type', 'hidden').wrap('<div class=\'input_token h5 m-b-1\'><span class=\'tag tag-primary\'><a class=\'close\'>&times;</a><span class=\'input_token_text\'>'+$(this).val()+'</span></span></div>');
});

$(document).on('click', '.input_token .close',function(e){
	$(this).parents('.input_token').remove();
});


$(document).on('click', '#view_article_version_edit', function(e){
	window.location.assign('/article/'+article_id+'/edit?article_version='+$('#article_versions').val());
});

$(document).on('click', '#view_article_version', function(e){
	var article_version_window = window.open('/article/'+article_id+'?article_version='+$('#article_versions').val(), 'article_version_window');
});

$(document).on('input', 'input.form-control, textarea.form-control, select.form-control', function(){
	notsaved = true;
	$(this).parents('.form-group').addClass('has-danger');
});
$(document).on('click', '[type="submit"]', function(e){
	notsaved = false;
});

$(document).on('click', '[data-toggle="modal"]', function(e){
	
});


Mousetrap.bind('mod+s', function(e){
	e.preventDefault();
	$('#editorForm').submit();
});

$('#editorForm').on('submit', function(){
	notsaved = false;
});

Mousetrap.bind('e e', function(e){
	e.preventDefault();
	var loc = $('.edit_article_button').attr('href');
	if(loc)
		window.location.assign(loc);
});