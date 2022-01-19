<?php

namespace CDS;

class Utils
{
    public static function checkOptionCallback($option, $value, $callback, $save = true)
    {
        if ($old = get_option($option) != $value) {
            if ($save) {
                self::addOrUpdateOption($option, $value);
            }

            call_user_func_array($callback, [$old, $value]);
        }
    }

    public static function addOrUpdateOption($option, $value): void
    {
        if (get_option($option)) {
            update_option($option, $value);
        }

        add_option($option, $value);
    }

    public static function strContains($haystack, $needle): bool
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }

    public static function isWpEnv(): bool
    {
        if (isset($_SERVER) && isset($_SERVER['SERVER_PORT'])) {
            $port = $_SERVER['SERVER_PORT'];

            if ($port == 8888 || $port == 8889) {
                return true;
            }
        }
        return false;
    }

    public static function isWPEnvAdmin(): bool
    {
        $current_user = wp_get_current_user();

        if ($current_user) {
            $current_username = $current_user->user_login;

            if ($current_username === 'gcadmin') {
                return true;
            }
        }

        return false;
    }

    public static function addHttp($url, $protocol = "https")
    {

        $cleanUrl = $url;

        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            // add ":" if not localhost
            $colon = str_contains($url, "localhost") ? "" : ":";
            // don't add slashes if they exists i.e. //canada.ca
            // or if localhost
            $slashes = str_contains($url, "//") || $colon === "" ? "" : "//";
            // add http or https
            $cleanUrl = $protocol . $colon . $slashes . $url;
        }

        // Return url
        return rtrim($cleanUrl, '/');
    }
}
