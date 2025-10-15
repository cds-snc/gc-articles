<?php

require __DIR__ . '/../../../../vendor/autoload.php';

require_once __DIR__ . '/../inc/template-functions.php';

use PHPUnit\Framework\TestCase;

// Global variables to store mocked function returns
$GLOBALS['wp_test_mocks'] = [];

// Mock WordPress functions that are used in the tests
if (!function_exists('get_permalink')) {
    function get_permalink($id = 0, $leavename = false) {
        return $GLOBALS['wp_test_mocks']['get_permalink'][$id] ?? '';
    }
}

if (!function_exists('get_previous_post')) {
    function get_previous_post($in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
        return $GLOBALS['wp_test_mocks']['get_previous_post'] ?? null;
    }
}

if (!function_exists('get_next_post')) {
    function get_next_post($in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
        return $GLOBALS['wp_test_mocks']['get_next_post'] ?? null;
    }
}

if (!function_exists('_e')) {
    function _e($text, $domain = 'default') {
        echo $text;  // Just echo the text for testing
    }
}

class PrevNextLinksTest extends TestCase
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


    public function test_cds_prev_next_links()
    {
        $post = new \stdClass;
        $post->ID = 41;
        $post->post_title = "Post";
        $post->guid = "https://example.com";

        $prev_post = new \stdClass;
        $prev_post->ID = 42;
        $prev_post->post_title = "Post 1";
        $prev_post->guid = "https://example.com/page1";

        $next_post = new \stdClass;
        $next_post->ID = 43;
        $next_post->post_title = "Post 3";
        $next_post->guid = "https://example.com/page2";

        // Mock the WordPress functions
        $GLOBALS['wp_test_mocks']['get_permalink'][42] = $prev_post->guid;
        $GLOBALS['wp_test_mocks']['get_permalink'][43] = $next_post->guid;
        $GLOBALS['wp_test_mocks']['get_previous_post'] = $prev_post;
        $GLOBALS['wp_test_mocks']['get_next_post'] = $next_post;

        ob_start();
        cds_prev_next_links();
        $links = ob_get_contents();
        ob_end_clean();

        expect($this->containsString($links, $prev_post->guid))->toBeTrue();
        expect($this->containsString($links, $next_post->guid))->toBeTrue();
    }
}




