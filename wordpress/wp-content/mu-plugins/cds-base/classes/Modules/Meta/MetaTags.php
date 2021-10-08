<?php

declare(strict_types=1);

namespace CDS\Modules\Meta;

class MetaTags
{
    const APPROX_META_DESCRIPTION_LENGTH = 150;

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
            $post_description = $this->getMetaFromContent($post_description);

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

    // return complete sentences instead of arbitrarily cutting the content to a given character length
    public function getMetaFromContent($text = ""): string
    {
        if (strlen($text) < self::APPROX_META_DESCRIPTION_LENGTH) {
            return $text; // return strings less than 150 characters
        }

        $text_arr = explode(". ", $text);
        return $this->getMeta($text_arr); // pass in array of sentences
    }

    private function getMeta($text_arr = [], $description = ''): string
    {
        // if empty, set to first element in array. if not empty, append next element of array
        $description = empty(($description)) ? array_shift($text_arr) : $description . ". " . array_shift($text_arr);

        if (strlen($description) > self::APPROX_META_DESCRIPTION_LENGTH) {
            // if over 150 characters, return string
            return str_ends_with($description, '.') ? $description : $description . '.';
        }

        return $this->getMeta($text_arr, $description);
    }
}
