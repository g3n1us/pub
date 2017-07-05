/**
 * Copyright (c) 2014-2016, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Simple CKEditor Widget (Part 2).
 *
 * Created out of the CKEditor Widget SDK:
 * http://docs.ckeditor.com/#!/guide/widget_sdk_tutorial_2
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'g3n1us_component', {
	// This plugin requires the Widgets System defined in the 'widget' plugin.
	requires: 'widget',

	// Register the icon used for the toolbar button. It must be the same
	// as the name of the widget.
	icons: 'g3n1us_component',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {
		// Register the editing dialog.
		CKEDITOR.dialog.add( 'g3n1us_component', this.path + 'dialogs/g3n1us_component.js' );

		// Register the simplebox widget.
		editor.widgets.add( 'g3n1us_component', {
			// Allow all HTML elements, classes, and styles that this widget requires.
			// Read more about the Advanced Content Filter here:
			// * http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter
			// * http://docs.ckeditor.com/#!/guide/plugin_sdk_integration_with_acf
			allowedContent:
				'div[data-subfolder][data-mdc_component]',

			// Minimum HTML which is required by this widget to work.
			requiredContent: 'div[data-subfolder][data-mdc_component]',


			// Define the template of a new Simple Box widget.
			// The template will be used when creating new instances of the Simple Box widget.
			template:
			'<div data-subfolder="userdata/user-us-east-1:643c4fb0-9762-4f46-9723-5e6fa2da3b0e/" data-mdc_component="VideoSlider"></div>',

			// Define the label for a widget toolbar button which will be automatically
			// created by the Widgets System. This button will insert a new widget instance
			// created from the template defined above, or will edit selected widget
			// (see second part of this tutorial to learn about editing widgets).
			//
			// Note: In order to be able to translate your widget you should use the
			// editor.lang.simplebox.* property. A string was used directly here to simplify this tutorial.
			button: 'Embed a G3N1Us Component',

			// Set the widget dialog window name. This enables the automatic widget-dialog binding.
			// This dialog window will be opened when creating a new widget or editing an existing one.
			dialog: 'g3n1us_component',

			// Check the elements that need to be converted to widgets.
			//
			// Note: The "element" argument is an instance of http://docs.ckeditor.com/#!/api/CKEDITOR.htmlParser.element
			// so it is not a real DOM element yet. This is caused by the fact that upcasting is performed
			// during data processing which is done on DOM represented by JavaScript objects.
			upcast: function( element ) {
				window.ttt = element;
// 				console.log(element);
				// Return "true" (that element needs to converted to a Simple Box widget)
				// for all <div> elements with a "simplebox" class.
				return 'data-mdc_component' in element.attributes;
// 				return $(element).attr('data-mdc_component') !== null;
// 				return element.attributes. ('data-mdc_component');
			},

			// When a widget is being initialized, we need to read the data ("align" and "width")
			// from DOM and set it by using the widget.setData() method.
			// More code which needs to be executed when DOM is available may go here.
			init: function() {
				window.eee = this.element;
				
				var subfolder = this.element.data('subfolder');
// 				var subfolder = $(this.element).data('subfolder');
				console.log(subfolder);

				if(subfolder)
					var user_id = subfolder.split(':')[1].slice(0,-1);
				if ( user_id )
					this.setData( 'user_id', user_id );
				else if(localStorage.g3n1us_components_user_id)
					this.setData( 'user_id', localStorage.g3n1us_components_user_id );
				var component = this.element.data('mdc_component');
				if (component)
					this.setData( 'component', component );
			},

			// Listen on the widget#data event which is fired every time the widget data changes
			// and updates the widget's view.
			// Data may be changed by using the widget.setData() method, which we use in the
			// Simple Box dialog window.
			data: function() {
				// Check whether "width" widget data is set and remove or set "width" CSS style.
				// The style is set on widget main element (div.simplebox).
				var subfolder = 'userdata/user-us-east-1:'+this.data.user_id+'/';
				
				this.element.setAttribute('data-subfolder', subfolder);
				this.element.setAttribute('data-mdc_component', this.data.component);
			}
		} );
	}
} );
