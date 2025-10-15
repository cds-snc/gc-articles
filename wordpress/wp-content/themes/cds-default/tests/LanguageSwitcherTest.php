<?php

require __DIR__ . "/../../../../vendor/autoload.php";

require_once __DIR__ . "/../inc/template-functions.php";

use PHPUnit\Framework\TestCase;

// Global variables to store mocked function returns
$GLOBALS['wp_test_mocks'] = [];

// Mock WordPress functions that are used in the tests
if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key, $single = false) {
        return $GLOBALS['wp_test_mocks']['get_post_meta'] ?? false;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($hook, $value, ...$args) {
        return $GLOBALS['wp_test_mocks']['apply_filters'][$hook] ?? $value;
    }
}

if (!function_exists('icl_get_languages')) {
    function icl_get_languages() {
        return $GLOBALS['wp_test_mocks']['icl_get_languages'] ?? [];
    }
}

class LanguageSwitcherTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset mock data
        $GLOBALS['wp_test_mocks'] = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Reset mock data
        $GLOBALS['wp_test_mocks'] = [];
    }

    public function containsString($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }

    public function test_default_language_text()
    {
        $text = get_language_text();

        $this->assertEquals(["full" => "English", "abbr" => "en"], $text);
    }

    public function test_fr_language_text()
    {
        $text = get_language_text("fr");

        $this->assertEquals(["full" => "Français", "abbr" => "fr"], $text);
    }

    public function test_language_switcher()
    {
        $langs = [
            "en" => [
                "id" => 1,
                "active" => 1,
                "default_locale" => "en_US",
                "native_name" => "English",
                "missing" => 0,
                "translated_name" => "English",
                "language_code" => "en",
                "country_flag_url" =>
                    "http://yourdomain/wpmlpath/res/flags/en.png",
                "url" => "http://yourdomain/about",
            ],
            "fr" => [
                "id" => 4,
                "active" => 0,
                "default_locale" => "fr_CA",
                "native_name" => "Français",
                "missing" => 0,
                "translated_name" => "French",
                "language_code" => "fr",
                "country_flag_url" =>
                    "http://yourdomain/wpmlpath/res/flags/fr.png",
                "url" => "http://yourdomain/fr/a-propos",
            ],
        ];

        // Mock the wpml_active_languages filter
        $GLOBALS['wp_test_mocks']['apply_filters']['wpml_active_languages'] = $langs;
        
        // Mock icl_get_languages function
        $GLOBALS['wp_test_mocks']['icl_get_languages'] = [];
        
        global $wp_query;
        $wp_query = new stdClass;
        $wp_query->post = new stdClass;
        $wp_query->post->ID = 1;

        // Mock get_post_meta to return false
        $GLOBALS['wp_test_mocks']['get_post_meta'] = false;

        $nav = language_switcher();

        expect($this->containsString($nav, $langs["fr"]["url"]))->toBeTrue();
        expect(
            $this->containsString($nav, $langs["fr"]["native_name"])
        )->toBeTrue();
    }

    public function test_manual_language_switcher()
    {
        global $wp_query;
        $wp_query = new stdClass;
        $wp_query->post = new stdClass;
        $wp_query->post->ID = 1;

        // Mock get_post_meta to return valid JSON
        $GLOBALS['wp_test_mocks']['get_post_meta'] = '{"active":false,"translated_name":"English","url":"http//:test.com"}';

        $nav = language_switcher();

        expect($this->containsString($nav, "http//:test.com"))->toBeTrue();
        expect(
            $this->containsString($nav, 'English')
        )->toBeTrue();
    }

    public function test_convert_url_hack()
    {
        $url = "http://test.com/fr/category/french-slug";
        $target_lang = "en";
        $converted_url = convert_url($url, $target_lang);
        expect($converted_url)->toEqual("http://test.com/category/french-slug");

        $url = "http://test.com/category/english-slug";
        $target_lang = "fr";
        $converted_url = convert_url($url, $target_lang);
        expect($converted_url)->toEqual("http://test.com/fr/category/english-slug");
        
        $url = "http://test.com/non-category-page";
        $target_lang = "fr";
        $converted_url = convert_url($url, $target_lang);
        expect($converted_url)->toEqual("http://test.com/non-category-page");        
    }
}
