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

if (!function_exists('esc_url')) {
    function esc_url($url) {
        return $GLOBALS['wp_test_mocks']['esc_url']($url);
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return $GLOBALS['wp_test_mocks']['esc_attr']($text);
    }
}

if (!function_exists('wp_kses')) {
    function wp_kses($html, $allowed_html) {
        return $GLOBALS['wp_test_mocks']['wp_kses']($html, $allowed_html);
    }
}

if (!function_exists('wp_reset_query')) {
    function wp_reset_query() {
    }
}

class LanguageSwitcherTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset mock data
        $GLOBALS['wp_test_mocks'] = [];
        $GLOBALS['wp_test_mocks']['esc_url'] = fn($url) => $url;
        $GLOBALS['wp_test_mocks']['esc_attr'] = fn($text) => $text;
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
        $GLOBALS['wp_test_mocks']['get_post_meta'] = '{"active":false,"translated_name":"English","url":"https://test.com/en/"}';

        $nav = language_switcher();

        expect($this->containsString($nav, "https://test.com/en/"))->toBeTrue();
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

    public function test_language_url_is_escaped_after_category_conversion()
    {
        $raw_url = '/category/test" onmouseover="alert(1)';
        $escaped_url = '/fr/category/test%20onmouseover=alert(1)';
        $GLOBALS['wp_test_mocks']['esc_url'] = function ($url) use ($escaped_url) {
            $this->assertSame('/fr/category/test" onmouseover="alert(1)', $url);
            return $escaped_url;
        };

        $links = language_switcher_output([
            ['active' => false, 'translated_name' => 'French', 'url' => $raw_url],
        ]);

        $this->assertCount(1, $links);
        $this->assertStringContainsString('href="' . $escaped_url . '"', $links[0]);
        $this->assertStringNotContainsString('onmouseover="', $links[0]);
    }

    public function test_manual_language_switcher_uses_escaped_url()
    {
        global $wp_query;
        $wp_query = (object)['post' => (object)['ID' => 1]];
        $GLOBALS['wp_test_mocks']['get_post_meta'] =
            '{"active":false,"translated_name":"English","url":"javascript:alert(1)"}';
        $GLOBALS['wp_test_mocks']['esc_url'] = function ($url) {
            $this->assertSame('javascript:alert(1)', $url);
            return '';
        };

        $link = manual_language_switcher();

        $this->assertStringContainsString('href=""', $link);
        $this->assertStringNotContainsString('javascript:', $link);
    }

    public function test_language_attributes_are_escaped()
    {
        $attributes = [];
        $GLOBALS['wp_test_mocks']['esc_attr'] = function ($text) use (&$attributes) {
            $attributes[] = $text;
            return 'escaped-' . $text;
        };

        $links = language_switcher_output([
            ['active' => false, 'translated_name' => 'English', 'url' => '/en/'],
        ]);

        $this->assertSame(['en', 'en', 'English'], $attributes);
        $this->assertStringContainsString('lang="escaped-en"', $links[0]);
        $this->assertStringContainsString('hreflang="escaped-en"', $links[0]);
        $this->assertStringContainsString('title="escaped-English"', $links[0]);
    }

    public function test_custom_breadcrumb_uses_restricted_html_allowlist()
    {
        global $wp_query;
        $wp_query = (object)['post' => (object)['ID' => 1]];
        $raw_html = '<li><a href="/" onclick="alert(1)">Home</a></li><img src=x onerror=alert(1)>';
        $safe_html = '<li><a href="/">Home</a></li>';
        $GLOBALS['wp_test_mocks']['get_post_meta'] = $raw_html;
        $GLOBALS['wp_test_mocks']['wp_kses'] = function ($html, $allowed_html) use ($raw_html, $safe_html) {
            $this->assertSame($raw_html, $html);
            $this->assertSame([
                'li' => [],
                'a' => ['href' => true, 'title' => true, 'aria-current' => true],
            ], $allowed_html);
            return $safe_html;
        };

        $breadcrumb = custom_field_breadcrumb();

        $this->assertStringContainsString('<ol class="breadcrumb">' . $safe_html . '</ol>', $breadcrumb);
        $this->assertStringNotContainsString('onclick', $breadcrumb);
        $this->assertStringNotContainsString('<img', $breadcrumb);
    }

    public function test_missing_custom_breadcrumb_remains_empty()
    {
        global $wp_query;
        $wp_query = (object)['post' => (object)['ID' => 1]];
        $GLOBALS['wp_test_mocks']['get_post_meta'] = '';

        $this->assertSame('', custom_field_breadcrumb());
    }
}
