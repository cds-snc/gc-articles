<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;

add_filter('render_block', 'cds_date_block', 10, 3);

function cds_date_block($block_content, $block)
{
    if ($block['blockName'] !== 'core/post-date') {
        return $block_content;
    }

    try {
        $dom = new Dom();
        $dom->loadStr($block_content);
        $time = $dom->find('time');

        if ($time && $time[0] && is_array($time[0] || is_string($time[0]))) {
            return str_replace($time[0], '[' . $time[0] . ']', $block_content);
        }

        return '';
    } catch (Exception $e) {
        return $block_content;
    }
}

// define the navigation_markup_template callback
function filter_navigation_markup_template($template, $class)
{
    // make filter magic happen here...
    $output = '<nav class="navigation 1 %1$s mrgn-tp-xl" role="navigation" aria-label="%4$s">';
    $output .= '<ul class="pager">%3$s</ul>';
    $output .= '</nav>';
    return $output;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function cds_body_classes(array $classes): array
{
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (! is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}

add_filter('body_class', 'cds_body_classes');
