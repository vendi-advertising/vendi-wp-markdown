<?php
/*
Plugin Name: Vendi WP Markdown
Description: Allows authors to write in Markdown.
Version: 1.0.1
Author: Chris Haas
Author URI: https://www.vendiadvertising.com/
Text Domain: vendi-wp-markdown
Domain Path: /languages

PHP Markdown Lib
Copyright (c) 2004-2016 Michel Fortin
<https://michelf.ca/>
All rights reserved.

Based on Markdown
Copyright (c) 2003-2005 John Gruber
<https://daringfireball.net/>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

*   Redistributions of source code must retain the above copyright
    notice, this list of conditions and the following disclaimer.

*   Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the
    distribution.

*   Neither the name "Markdown" nor the names of its contributors may
    be used to endorse or promote products derived from this software
    without specific prior written permission.

This software is provided by the copyright holders and contributors "as
is" and any express or implied warranties, including, but not limited
to, the implied warranties of merchantability and fitness for a
particular purpose are disclaimed. In no event shall the copyright owner
or contributors be liable for any direct, indirect, incidental, special,
exemplary, or consequential damages (including, but not limited to,
procurement of substitute goods or services; loss of use, data, or
profits; or business interruption) however caused and on any theory of
liability, whether in contract, strict liability, or tort (including
negligence or otherwise) arising in any way out of the use of this
software, even if advised of the possibility of such damage.
*/

define( 'VENDI_WP_MARKDOWN_FILE', __FILE__ );
define( 'VENDI_WP_MARKDOWN_PATH', dirname( __FILE__ ) );
define( 'VENDI_WP_MARKDOWN_URL', plugin_dir_url( __FILE__ ) );

require_once VENDI_WP_MARKDOWN_PATH . '/includes/autoload.php';

require_once VENDI_WP_MARKDOWN_PATH . '/includes/hooks.php';

