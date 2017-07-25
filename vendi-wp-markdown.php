<?php
/*
Plugin Name: Vendi WP Markdown
Plugin URI: https://vendiadvertising.com/
Description: Allows authors to write in Markdown.
Version: 0.1
Author: Chris Haas
Author URI: https://vendiadvertising.com/
Text Domain: vendi-wp-markdown
*/

define( 'VENDI_WP_MARKDOWN_FILE', __FILE__ );
define( 'VENDI_WP_MARKDOWN_PATH', dirname( __FILE__ ) );
define( 'VENDI_WP_MARKDOWN_URL', plugin_dir_url( __FILE__ ) );

require_once VENDI_WP_MARKDOWN_PATH . '/includes/autoload.php';

require_once VENDI_WP_MARKDOWN_PATH . '/includes/hooks.php';

