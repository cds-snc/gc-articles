<?php

use Wa72\HtmlPageDom\HtmlPage;

function cds_filter_core_buttons($block_content, $block)
{
    if ('core/buttons' === $block['blockName']) {
        try {
            $defaults = [];
            $args = wp_parse_args($block['attrs'], $defaults);
            $links = '';

            foreach ($block['innerBlocks'] as $block) {
                $html = $block['innerContent'][0];
                $args = wp_parse_args($block['attrs'], [
                    'className' => 'button',
                ]);
                $className = $args['className'];

                $crawler = @new HtmlPage($html);
                $link = $crawler->filter('a');
                $link->addClass($className);
                $links .= $link;
            }

            return $links;
        } catch (Exception $e) {
            //no-op
        }
    }

    return $block_content;
}

add_filter('render_block', 'cds_filter_core_buttons', 10, 3);
