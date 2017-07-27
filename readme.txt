=== Vendi WP Markdown ===
Plugin Name: Vendi WP Markdown
Contributors: chrisvendiadvertisingcom
Tags: markdown, tinymce
Version: 1.0.1
Author: Chris Haas
Author URI: https://www.vendiadvertising.com/
Requires at least: 4.7
Tested up to: 4.8
Stable tag: trunk
Text Domain: vendi-wp-markdown
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows authors to write in Markdown. Pretty straightforward.


== Description ==

Check the box per-post to eable Markdown for the current post or page and start typing.

This plugin uses [Michel Fortin's](https://michelf.ca/) [awesome library](https://github.com/michelf/php-markdown) which is based on the [Markdown created by John Gruber](https://daringfireball.net/). All credit for anything related to Markdown should go to those people, this plugin just gives access to Markdown from WordPress.

Markdown syntax can be viewed [here](https://daringfireball.net/projects/markdown/).


== Installation ==

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
1. Search for 'Vendi WP Markdown'
1. Click 'Install Now' and activate the plugin


== Screenshots ==

1. Checkbox to enable Markdown support per-post
2. If Markdown is enabled then TinyMCE's WYSIWYG will be disabled


== Frequently Asked Questions ==

= Can I preview my Markdown before publishing? =

At this time we are disabling TinyMCE's WYSIWYG editor in Markdown mode so you cannot switch back and forth between "Visual" and "Text" modes. We are considering adding support for this in the future if enough people request it.

= Does this work with multiple TinyMCE instances? =

At this moment no, this feature is not supported.

= Does this work with Multisite? =

Although it has not been tested there is no reason that this shouldn't work with Multisite.

= What exactly does this plugin do? =

WordPress stores your post's body in a field in the database called `post_content` however it also allows for a second field called `post_content_filtered`. When you hit "Save" on your post/page this plugin converts your content from Markdown to HTML, the Markdown gets stored in `post_content_filtered` and the HTML gets stored in `post_content`. Because we are rendering on the admin side the rest of WordPress only ever interacts with the HTML.

= Will this plugin slow my site down? =
No. Because we render on the admin side of things (see the previous question for more details) individual page-renders won't be affected by this plugin.

= Is Markdown extra supported? =
Not at this time.

= Can I customize the configuration of the Markdown parser? =

Not at this time.

== Changelog ==

= 1.0.1 =
Renamed variable

= 1.0.0 =
First release

== Roadmap ==
Version 1 is the very simple proof of concept that just works.  It is intended to be used by people who know what Markdown is and when they should and shouldn't use it. Specifically it doesn't address multiple editors on a single page nor does it support widgets. There are also no global settings, just on and off per post/page.

Version 2 might possibly add support for previewing Markdown and possibly converting HTML to Markdown via the [PHP League's awesome library](https://github.com/thephpleague/html-to-markdown). I'd also like to get WordPress's preview system working but that might be blocked by [this](https://core.trac.wordpress.org/ticket/20299) and/or [this](https://core.trac.wordpress.org/ticket/20564).


== Copyright and License ==

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
