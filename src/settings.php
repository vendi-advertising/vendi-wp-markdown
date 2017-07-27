<?php

namespace Vendi\WPMarkdown;

use Vendi\Shared\utils;

class settings
{
    const DB_PREFIX = 'vendi-wp-markdown-';

    const NAME_ENABLED = 'enabled';

    public static function get_name( $name )
    {
        return self::DB_PREFIX . $name;
    }

    public static function get_name_enabled()
    {
        return self::get_name( self::NAME_ENABLED );
    }

    public static function get_boolean_option( $post_or_id, $name, $default = false )
    {
        $post = get_post( $post_or_id );
        $value = get_post_meta( $post->ID, $name, true );

        if( null === $value || '' === trim( $value ) )
        {
            return $default;
        }

        if( ! utils::is_integer_like( $value ) )
        {
            return $default;
        }

        return 1 === (int)$value;
    }

    public static function is_post_markdown_enabled( $post_or_id, $default = false )
    {
        $name = settings::get_name_enabled();
        return settings::get_boolean_option( $post_or_id, $name, $default );
    }
}
