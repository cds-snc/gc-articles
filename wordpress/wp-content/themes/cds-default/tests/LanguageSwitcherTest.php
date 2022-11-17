<?php

require __DIR__ . "/../../../../vendor/autoload.php";

// Now call the bootstrap method of WP Mock
WP_Mock::bootstrap();

require_once __DIR__ . "/../inc/template-functions.php";

class LanguageSwitcherTest extends \WP_Mock\Tools\TestCase
{
    public function setUp(): void
    {
        \WP_Mock::setUp();
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
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

        \WP_Mock::onFilter("wpml_active_languages")
            ->with(null, "orderby=id&order=desc")
            ->reply($langs);

        \WP_Mock::passthruFunction("icl_get_languages");
        global $wp_query;
        $wp_query = new stdClass;
        $wp_query->post = new stdClass;
        $wp_query->post->ID = 1;

        \WP_Mock::userFunction('get_post_meta', array(
            'times' => 1,
            'args' => array(1, 'locale_switch_link', true),
            'return' => false
        ));

        $nav = language_switcher();

        expect($this->containsString($nav, $langs["fr"]["url"]))->toBeTrue();
        expect(
            $this->containsString($nav, $langs["fr"]["native_name"])
        )->toBeTrue();
    }

    public function test_manual_language_switcher_noop()
    {
        global $wp_query;
        $wp_query = new stdClass;
        $wp_query->post = new stdClass;
        $wp_query->post->ID = 1;

        \WP_Mock::userFunction('get_post_meta', array(
            'times' => 1,
            'args' => array(1, 'locale_switch_link', true),
            'return' => '{}' // test "incorrect data"
        ));

        $nav = language_switcher();

        expect($nav)->toEqual("");
    }

    public function test_manual_language_switcher()
    {
        global $wp_query;
        $wp_query = new stdClass;
        $wp_query->post = new stdClass;
        $wp_query->post->ID = 1;

        \WP_Mock::userFunction('get_post_meta', array(
            'times' => 1,
            'args' => array(1, 'locale_switch_link', true),
            'return' => '{"active":false,"translated_name":"English","url":"http//:test.com"}'
        ));

        $nav = language_switcher();

        expect($this->containsString($nav, "http//:test.com"))->toBeTrue();
        expect(
            $this->containsString($nav, 'English')
        )->toBeTrue();
    }

    public function test_convert_url_hack()
    {
        expect([
            ["http://test.com/category/test", "fr", "http://test.com/fr/category/test"],
            ["http://test.com/fr/category/test", "en", "http://test.com/category/test"]
            ])->each(fn ($args) => convert_url($args[0], $args[1])->toEqual($args[2]));
    }
}
