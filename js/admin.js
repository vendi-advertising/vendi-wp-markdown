/*jslint maxparams: 4, maxdepth: 4, maxstatements: 20, maxcomplexity: 8 */
/*global jQuery, i18n*/
(function( )
{
    'use strict';                         //Force strict mode

    var

        //These are defaults
        oldVisualLabel,
        oldVTextLabel,

        set_editor = function( is_html_disabled )
        {
            var
                //Grab our two buttons
                visualButton = document.getElementById( 'content-tmce' ),
                textButton = document.getElementById( 'content-html' )
            ;

            if( is_html_disabled )
            {
                //Store our old WP translated values
                oldVisualLabel = visualButton.innerText;
                oldVTextLabel = textButton.innerText;

                //Disable the button
                visualButton.setAttribute( 'disabled', 'disabled' );

                //Set text telling the user that the tab is disabled
                visualButton.innerText = i18n.tab_visual_disabled;

                //Change Text to Markdown
                textButton.innerText = i18n.tab_markdown_enabled;

                //Click the text button to get the editor to perform
                //the switching logic
                textButton.click();
            }
            else
            {
                //Reset things back to normal
                visualButton.innerText = oldVisualLabel;
                visualButton.removeAttribute( 'disabled' );

                textButton.innerText = oldVTextLabel;
            }
        },

        onchange = function( obj )
        {
            var
                checked = jQuery( this ).is( ':checked' )
            ;

            set_editor( checked );
        },

        onload = function()
        {
            var
                //Grab our checkbox
                cb = document.getElementById( i18n.checkbox_name )
            ;

            //Make sure we have something
            if( ! cb )
            {
                console.warn( i18n.no_checkbox_found );
                return;
            }

            if( jQuery( cb ).is( ':checked' ) )
            {
                set_editor( true );
            }

            //Watch for all changes of the checkbox
            jQuery( cb ).change( onchange );
        },

        init = function()
        {
            //Bind to the ready event to make sure that the DOM is loaded
            jQuery( document ).ready( onload );
        }
    ;

    //Kick everything off
    init( );
}
()
);
