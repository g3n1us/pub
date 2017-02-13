CKEDITOR.plugins.add( 'mdc_ad', {
    requires: 'widget',

    icons: 'mdc_ad',

    init: function( editor ) {
        editor.widgets.add( 'mdc_ad', {

            button: 'Insert an ad',

            template:
                '<div class="mdc--ad" data-size="300x250"></div>',

/*
            editables: {
                title: {
                    selector: '.simplebox-title',
                    allowedContent: 'br strong em'
                },
                content: {
                    selector: '.simplebox-content',
                    allowedContent: 'p br ul ol li strong em'
                }
            },

            allowedContent:
                'div(!simplebox); div(!simplebox-content); h2(!simplebox-title)',
*/

            requiredContent: 'div(mdc--ad)',

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'mdc--ad' );
            }
        } );
    }
} );	
