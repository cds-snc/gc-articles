<?php

declare(strict_types=1);

namespace CDS\Modules\Meta;

class MetaTags
{
    public function __construct()
    {
        add_action('wp_head', [$this, 'addMetaTags']);
    }

    public function addMetaTags()
    {
        global $post;
        $base_meta_tag = "<meta name='description' content='%s' />\n";

        if (is_singular()) {
            $post_description = strip_shortcodes(strip_tags($post->post_content));
            $post_description = trim(str_replace(array("\n", "\r", "\t"), ' ', $post_description));
            $post_description = mb_substr($post_description, 0, 150, 'utf8');

            printf($base_meta_tag, $post_description);
        }
        if (is_home()) {
            printf($base_meta_tag, get_bloginfo("description", "display"));
        }
        if (is_category()) {
            $category_description = strip_tags(category_description());
            printf($base_meta_tag, $category_description);
        }
    }
}
