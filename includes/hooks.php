<?php

use Michelf\Markdown;
use Vendi\Shared\utils;
use Vendi\WPMarkdown\settings;

add_action(
            'plugins_loaded',
            function()
            {
                load_plugin_textdomain( 'vendi-wp-markdown', false, basename( VENDI_WP_MARKDOWN_PATH ) . '/languages' );
            }
        );

//Create a checkbox that flags that this post supports markdown.
add_action(
            'post_submitbox_misc_actions',
            function( $post )
            {
                //TODO: This should filter for supported post types

                //Grab the name of the setting which we use for the HTML ID and name
                $name = settings::get_name_enabled();

                //Does this post support markdown?
                $value = settings::is_post_markdown_enabled( $post );

                //Output our field in the publish meta box
                echo sprintf(
                    '
                        <div class="misc-pub-section">
                            <label>
                                <input type="checkbox" %1$s value="1" name="%2$s" id="%2$s" />
                                %3$s
                            </label>
                        </div>
                    ',
                    ( $value ? ' checked="checked" ' : '' ),
                    esc_attr( $name ),
                    __( 'Process as Markdown?', 'vendi-wp-markdown' )
                );
            }
         );

//This function only handles actually saving the checkbox, not the markdown/HTML itself
add_action(
            'save_post',
            function( $post_id, $post, $update )
            {

                //Ignore post revisions. Can't remember why.
                if ( wp_is_post_revision( $post_id ) )
                {
                    return;
                }

                $old_value = settings::is_post_markdown_enabled( $post );

                //The HTML form name
                $name = settings::get_name_enabled();
                $new_value = 1 === utils::get_post_value_int( $name, false );

                //If we've got nothing to do then... do nothing.
                if( $old_value === $new_value )
                {
                    return;
                }

                //If the new value is true
                if( $new_value )
                {
                    //Set this field to true
                    update_post_meta( $post_id, $name, 1 );
                }

                //Otherwise
                else
                {
                    //Don't set it to false, instead just delete the key
                    delete_post_meta( $post_id, $name );
                }
            },
            10,
            3
        );

//Add some CSS and JS to the backend
add_action(
            'admin_enqueue_scripts',
            function()
            {
                //TODO: Change filemtime over to static variable for perf. filemtime is great during
                //debugging/testing but is a small perf hit in production.

                $name = settings::get_name_enabled();

                $file = 'js/admin.js';
                wp_register_script( 'vendi-wp-markdown-admin-js', VENDI_WP_MARKDOWN_URL . $file, array(), filemtime( VENDI_WP_MARKDOWN_PATH . '/' . $file ), true );
                $translation_array = array(
                                            'tab_visual_disabled'   => __( 'Visual tab disabled for Markdown', 'vendi-wp-markdown' ),
                                            'tab_markdown_enabled'  => __( 'Markdown', 'vendi-wp-markdown' ),
                                            'no_checkbox_found'     => __( 'Could not find markdown checkbox... exiting', 'vendi-wp-markdown' ),
                                            'checkbox_name'         => $name,
                                        );
                wp_localize_script( 'vendi-wp-markdown-admin-js', 'i18n', $translation_array );
                wp_enqueue_script( 'vendi-wp-markdown-admin-js' );

                $file = 'css/admin.css';
                wp_enqueue_style( 'vendi-wp-markdown-admin-css', VENDI_WP_MARKDOWN_URL . $file, array(), filemtime( VENDI_WP_MARKDOWN_PATH . '/' . $file ), 'all' );
            }
        );

//When TinyMCE is loaded in EDIT mode this is called.
add_filter(
            'edit_post_content',
            function( $value, $post_id )
            {
                //If we don't have markdown enabled then just returne what we
                //were given, that's the polite thing to do.
                if( ! settings::is_post_markdown_enabled( $post_id ) )
                {
                    return $value;
                }

                //Grab the original post in raw mode which bypasses most filters
                $post = get_post( $post_id, OBJECT, 'raw' );

                //Return the markdown
                return $post->post_content_filtered;
            },
            10,
            2
        );

//This handles the actual markdown conversion.
//NOTE: This action is registered with a higher priority to guarantee that the
//checkbox is setup first so that is_post_markdown_enabled works as expected.
add_action(
            'save_post',
            function( $post_id, $post, $update )
            {
                //We're going to save within a save (that's so meta) so we need
                //to flag below when we do that and catch it here.
                $filter_name = "vendi/wp-markdown/inside_save/post_$post_id";

                //Are we already in a save? If so, avoid an infinite loop.
                if( apply_filters( $filter_name, false ) )
                {
                    return;
                }

                //For autosaves do nothing
                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                {
                    return;
                }

                //Don't do anything on post revisions. Still can't remember why.
                if( wp_is_post_revision( $post_id ) )
                {
                    return;
                }

                //Weird case of not having a post. When could this happen? Don't
                //know. But just in case.
                if( ! $post instanceof \WP_Post )
                {
                    return;
                }

                //If we don't have any content to speak of then also bail
                if( ! $post->post_content )
                {
                    return;
                }

                //Finally, if markdown isn't enabled for this post bail
                if( ! settings::is_post_markdown_enabled( $post_id ) )
                {
                    return;
                }

                //Okay, create a filter saying that we're in a save for this
                //specific post
                add_filter( "vendi/wp-markdown/inside_save/post_$post_id", __return_true );

                //Grab what was entered
                $markdown = $post->post_content;

                //And convert that to HTML
                $html     = Markdown::defaultTransform( $markdown );

                //Update the post saying that our converted HTML is the new true
                //content and store our markdown in the filtered field.
                wp_update_post(
                                array(
                                        'ID' => $post_id,
                                        'post_content' => $html,
                                        'post_content_filtered' => $markdown,
                                    )
                            );
            },
            20,
            3
        );

//Insert some hidden fields and force the HTML editor if in markdown mode
add_action(
            'edit_form_top',
            function( $post )
            {
                //Right now we're not doing anything with these fields but
                //eventually we might use them with the checkbox to pop up a
                //message when switching between modes.
                $original_post = get_post( $post, OBJECT, 'raw' );
                echo sprintf(
                                '<input type="hidden" id="vendi-wp-markdown_original_post-content" value="%1$s" />',
                                esc_attr( $original_post->post_content )
                            );

                echo sprintf(
                                '<input type="hidden" id="vendi-wp-markdown_original_post-content-filtered" value="%1$s" />',
                                esc_attr( $original_post->post_content_filtered )
                            );

                //No good reason for doing this here except that we have the
                //$original_post variable handy
                add_filter(
                            'wp_default_editor',
                            function( $editor ) use ( $original_post )
                            {
                                //If markdown is enabled for this post then set the
                                //default editor to the text editor, not the WYSIWYG.
                                if( settings::is_post_markdown_enabled( $original_post ) )
                                {
                                    return 'html';
                                }

                                return $editor;
                            }
                        );
            }
        );

