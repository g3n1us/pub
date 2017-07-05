/**
 * Copyright (c) 2014-2016, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 */

// Note: This automatic widget to dialog window binding (the fact that every field is set up from the widget
// and is committed to the widget) is only possible when the dialog is opened by the Widgets System
// (i.e. the widgetDef.dialog property is set).
// When you are opening the dialog window by yourself, you need to take care of this by yourself too.

CKEDITOR.dialog.add( 'g3n1us_component', function( editor ) {
	return {
		title: 'Edit Component',
		minWidth: 200,
		minHeight: 100,
		contents: [
			{
				id: 'info',
				elements: [
					{
						id: 'user_id',
						type: 'text',
						label: 'Enter Your User ID',
						user_id: 'xxx-xxx-xxxxxxx',
						setup: function( widget ) {
							this.setValue( widget.data.user_id );
						},
						commit: function( widget ) {
							localStorage.g3n1us_components_user_id = this.getValue();
							widget.setData( 'user_id', this.getValue() );
						}
					},	
					{
						id: 'component',
						type: 'select',
						label: 'Choose component',
						items: [
							[ editor.lang.common.notSet, '' ],
						],
						onLoad: function(widget){
						    var selectList = this;
						    window.www = this;
						    console.log(widget.data);
						    if(localStorage.g3n1us_components_user_id)
								$.get('https://g3n1us-components.s3.amazonaws.com/?delimiter=/&prefix=userdata/user-us-east-1:'+localStorage.g3n1us_components_user_id+'/', function(data){
									$(data).find('CommonPrefixes').each(function(){
										var path = $(this).find('Prefix').text();
										var pathparts = path.split('/');
										var projname = pathparts[pathparts.length - 2];
										selectList.add(projname, projname);
									});
								});
						},
						align: 'center',
						// When setting up this field, set its value to the "align" value from widget data.
						// Note: Align values used in the widget need to be the same as those defined in the "items" array above.
						setup: function( widget ) {
							this.items = ['SS', 'ss'];
							console.log(this)
							this.setValue( widget.data.component );
						},
						// When committing (saving) this field, set its value to the widget data.
						commit: function( widget ) {
							if(this.getValue())
								widget.setData( 'component', this.getValue() );
						}
					},
					{
						id: 'user_component',
						type: 'text',
						label: 'Enter the name of your component if it does not appear in the list',
						setup: function( widget ) {
							this.setValue( widget.data.component );
						},
						commit: function( widget ) {
							if(this.getValue())							
								widget.setData( 'component', this.getValue() );
						}
					},	
					
				]
			}
		]
	};
} );

// https://g3n1us-components.s3.amazonaws.com/?delimiter=/&prefix=userdata/user-us-east-1:643c4fb0-9762-4f46-9723-5e6fa2da3b0e/