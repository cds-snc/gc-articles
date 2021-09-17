<?php

namespace CDS;

class Utils
{
    public static function check_option_callback($option, $value, $callback, $save = true)
    {
        if ($old = get_option($option) != $value) {
            if ($save) {
                self::add_or_update_option($option, $value);
            }

            call_user_func_array($callback, [$old, $value]);
        }
    }

    public static function add_or_update_option($option, $value): void
    {
        if (get_option($option)) {
            update_option($option, $value);
        }

        add_option($option, $value);
    }

    public static function str_contains($haystack, $needle): bool
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }
}