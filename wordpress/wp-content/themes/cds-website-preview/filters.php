<?php

use Wa72\HtmlPageDom\HtmlPage;

function cds_filter_core_image($block_content, $block)
{
    if ('core/image' === $block['blockName']) {
        try {
            $block_html = @new HtmlPage($block_content);
            $img_html = $block_html->filter('figure img');
            // add the following styles to prevent the image from being improperly sized
            $img_html->css('max-width', '100%');
            $img_html->css('height', 'auto');
            return $img_html->outerHtml();
        } catch (Exception $e) {
            //no-op
        }
    }

    return $block_content;
}

add_filter('render_block', 'cds_filter_core_image', 10, 3);

function cds_remove_wp_block_paragraph_class($content)
{
    return preg_replace_callback(
        '/<p\b([^>]*)>/i',
        function ($matches) {
            $attrs = preg_replace('/\s*\bwp-block-paragraph\b\s*/', '', $matches[1]);
            $attrs = preg_replace('/\s*class="\s*"/', '', $attrs);
            return '<p' . $attrs . '>';
        },
        $content
    );
}

add_filter('the_content', 'cds_remove_wp_block_paragraph_class', 11);
