<?php

require __DIR__ . '/../../../../vendor/autoload.php';

// Now call the bootstrap method of WP Mock
WP_Mock::bootstrap();

require_once __DIR__ . '/../inc/template-functions.php';

class PrevNextLinksTest extends \WP_Mock\Tools\TestCase
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

        \WP_Mock::userFunction('get_permalink')->with(42)->andReturn($prev_post->guid);
        \WP_Mock::userFunction('get_permalink')->with(43)->andReturn($next_post->guid);

        \WP_Mock::userFunction('get_previous_post')->with()->andReturn($prev_post);
        \WP_Mock::userFunction('get_next_post')->with()->andReturn($next_post);

        ob_start();
        cds_prev_next_links();
        $links = ob_get_contents();
        ob_end_flush();

        expect($this->containsString($links, $prev_post->guid))->toBeTrue();
        expect($this->containsString($links, $next_post->guid))->toBeTrue();
    }
}




