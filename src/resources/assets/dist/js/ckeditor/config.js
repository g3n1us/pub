/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/*
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
*/

CKEDITOR.editorConfig = function( config ) {
	config.allowedContent = true;
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Sourcedialog,NewPage,Print,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Underline,JustifyBlock,Language,BidiRtl,BidiLtr,Flash,Font,About,Outdent,Indent';
	
	config.uploadUrl = "/dashboard/upload/1";
	config.imageUploadUrl = "/dashboard/upload/1/"+article_id;
	config.imageUploadUrl = "/dashboard/upload/1";
	config.filebrowserBrowseUrl = "/dashboard/cklist/files";
// 	config.extraPlugins = 'amwimagebrowser,onchange,stylesheetparser,markdown,embed,image2';
	config.extraPlugins = 'stylesheetparser,markdown,embed,image2';
// 	config.removePlugins = 'pbckcode';
	
// 	config.imageBrowser_listUrl = "/ccm_files/cklist/" + new Date;
	
	config.stylesSet = [];
	config.contentsCss = '/app/2016/dist/css/public-compiled.min.css';
/*
	config.sharedSpaces = {
	    top: 'editor_toolbar',
	};
*/
};