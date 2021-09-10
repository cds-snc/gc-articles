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
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}

add_filter('body_class', 'cds_body_classes');

function get_current_page_ID(): int
{
    if(is_admin()){
        return 0;
    }

    global $wpdb;
    $actual_link = rtrim($_SERVER['REQUEST_URI'], '/');
    $parts = explode('/', $actual_link);

    if (empty($parts)) {
        return 0;
    }

    // note: this code will fail for the 'root' or 'homepage' as it doesn't have a slug
    $slug = end($parts);

    if (empty($slug)) {
        return 0;
    }

    if (!$query = wp_cache_get($slug.'_'._S_VERSION)) {
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
                        WHERE 
                            `post_status` = %s
                        AND
                            `post_name` = %s
                        AND
                            TRIM(`post_name`) <> ''
                        LIMIT 1",
            'publish',
            sanitize_title($slug)
        );

        // cache the query
        wp_cache_set($slug.'_'._S_VERSION, $query, '', 3600);
    }

    $post_id = $wpdb->get_var($query);

    if ($post_id) {
        return absint($post_id);
    }

    return 0;
}

function define_locale($locale)
{

    try {
        $page_id = get_current_page_ID();

        if ($page_id) {

            $custom_locale = get_post_meta($page_id, 'locale', true);
            $page_id . " " . $custom_locale;
            if ($custom_locale) {
                return $custom_locale;
            }

        }


    } catch (Exception $e) {
        // noop
    }

    return $locale;
}

add_filter('locale', 'define_locale', 10);

add_filter('gutenberg_can_edit_post', '__return_true', 5);
add_filter('use_block_editor_for_post', '__return_true', 5);
add_filter( 'user_can_richedit' , '__return_true', 50 );
