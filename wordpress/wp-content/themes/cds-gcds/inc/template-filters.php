<?php

declare(strict_types=1);

function get_current_page_ID(): int
{
    if (is_admin()) {
        return 0;
    }
    global $wpdb;
    $slug = basename(rtrim(wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/'));
    if (!$slug) {
        return 0;
    }
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_status = %s AND post_name = %s LIMIT 1",
        'publish',
        sanitize_title($slug)
    ));
}

function define_locale($locale)
{
    $id = get_current_page_ID();
    return $id ? (get_post_meta($id, 'locale', true) ?: $locale) : $locale;
}
add_filter('locale', 'define_locale');
add_filter('gutenberg_can_edit_post', '__return_true', 5);
add_filter('use_block_editor_for_post', '__return_true', 5);
add_filter('user_can_richedit', '__return_true', 50);
