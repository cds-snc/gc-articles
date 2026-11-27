<?php

declare(strict_types=1);

function get_active_language(): string
{
    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
        return ICL_LANGUAGE_CODE;
    }
    return explode('_', get_locale())[0];
}

function get_fip_url(): string
{
    return str_replace('http://', 'https://', get_option('fip_href') ?: home_url());
}

function convert_url($url, $lang): string
{
    if (!$url) {
        return '';
    }
    $path = wp_parse_url($url, PHP_URL_PATH) ?? '';
    if (str_contains($path, '/category/')) {
        return str_starts_with($path, '/category/')
            ? str_replace('/category/', "/$lang/category/", $url)
            : str_replace('/fr/', '/', $url);
    }
    return $url;
}

function cds_gcds_language_url(): string
{
    $manual = is_singular() ? get_post_meta(get_queried_object_id(), 'locale_switch_link', true) : '';
    if ($manual) {
        $language = json_decode($manual, true);
        if (!is_array($language) || !is_string($language['url'] ?? null) || !esc_url_raw($language['url'])) {
            error_log('cds-gcds: invalid locale_switch_link metadata');
        } else {
            $other = get_active_language() === 'fr' ? 'en' : 'fr';
            return convert_url(esc_url_raw($language['url']), $other);
        }
    }
    $languages = apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
    foreach ($languages ?: [] as $language) {
        if (!$language['active']) {
            return convert_url($language['url'], $language['language_code']);
        }
    }
    error_log('cds-gcds: no equivalent language page configured');
    return '';
}

function cds_gcds_menu_items(string $location): array
{
    $locations = get_nav_menu_locations();
    return isset($locations[$location]) ? (wp_get_nav_menu_items($locations[$location]) ?: []) : [];
}

function cds_gcds_menu_links(string $location): string
{
    $output = '';
    foreach (cds_gcds_menu_items($location) as $item) {
        if (!$item->menu_item_parent) {
            $output .= '<gcds-link href="' . esc_url($item->url) . '" target="' . esc_attr($item->target ?: '_self') . '">' . esc_html($item->title) . '</gcds-link>';
        }
    }
    return $output;
}

function cds_gcds_nav_items(array $items, int $parent = 0, bool $preserveTargets = false): string
{
    $output = '';
    foreach ($items as $item) {
        if ((int) $item->menu_item_parent !== $parent) {
            continue;
        }
        $isCurrent = $item->current ?? (get_queried_object_id() > 0 && (int) $item->object_id === get_queried_object_id());
        $current = $isCurrent ? 'true' : 'false';
        $link = $preserveTargets
            ? '<gcds-link href="' . esc_url($item->url) . '" target="' . esc_attr($item->target ?: '_self') . '">' . esc_html($item->title)
                . ($isCurrent ? '<gcds-sr-only>' . esc_html__('Current page', 'cds-snc') . '</gcds-sr-only>' : '') . '</gcds-link>'
            : '<gcds-nav-link href="' . esc_url($item->url) . '" current="' . $current . '">' . esc_html($item->title) . '</gcds-nav-link>';
        $children = cds_gcds_nav_items($items, (int) $item->ID, $preserveTargets);
        if ($children) {
            $entry = $preserveTargets
                ? '<gcds-details details-title="' . esc_attr($item->title) . '">' . $link . '<ul>' . $children . '</ul></gcds-details>'
                : '<gcds-nav-group menu-label="' . esc_attr($item->title) . '" open-trigger="' . esc_attr($item->title) . '">' . $link . $children . '</gcds-nav-group>';
        } else {
            $entry = $link;
        }
        $output .= $preserveTargets ? '<li>' . $entry . '</li>' : $entry;
    }
    return $output;
}

function get_top_nav(): string
{
    $items = cds_gcds_menu_items('header');
    if ($items) {
        _wp_menu_item_classes_by_context($items);
    }
    if (array_filter($items, static fn($item) => !empty($item->target) && $item->target !== '_self')) {
        return '<gcds-container slot="menu" layout="page"><nav aria-label="' . esc_attr__('Top menu', 'cds-snc') . '"><ul class="list-inline">' . cds_gcds_nav_items($items, 0, true) . '</ul></nav></gcds-container>';
    }
    return $items
        ? '<gcds-top-nav slot="menu" label="' . esc_attr__('Top menu', 'cds-snc') . '">' . cds_gcds_nav_items($items) . '</gcds-top-nav>'
        : '';
}

function get_side_nav(): string
{
    if (!is_page()) {
        return '';
    }
    $id = get_queried_object_id();
    $ancestors = get_post_ancestors($id);
    $root = $ancestors ? end($ancestors) : $id;
    $pages = get_pages(['child_of' => $root, 'sort_column' => 'menu_order,post_title']);
    if (!$pages) {
        return '';
    }
    $items = [];
    foreach ($pages as $page) {
        $items[] = (object) [
            'ID' => $page->ID,
            'object_id' => $page->ID,
            'menu_item_parent' => $page->post_parent,
            'url' => get_permalink($page),
            'title' => $page->post_title,
        ];
    }
    return '<gcds-side-nav label="' . esc_attr(get_the_title($root)) . '"><gcds-nav-link slot="home" href="' . esc_url(get_permalink($root)) . '" current="' . ($id === $root ? 'true' : 'false') . '">' . esc_html(get_the_title($root)) . '</gcds-nav-link>' . cds_gcds_nav_items($items, (int) $root) . '</gcds-side-nav>';
}

function cds_breadcrumb($sep = ''): string
{
    $custom = is_singular() ? get_post_meta(get_queried_object_id(), 'breadcrumb', true) : '';
    if ($custom) {
        $dom = cds_gcds_dom($custom);
        if ($dom) {
            $output = '<gcds-breadcrumbs slot="breadcrumb" hide-canada-link>';
            foreach ($dom->getElementsByTagName('a') as $link) {
                if (
                    $link->getAttribute('aria-current') !== 'page'
                    && untrailingslashit($link->getAttribute('href')) !== untrailingslashit(get_permalink(get_queried_object_id()))
                ) {
                    $output .= '<gcds-breadcrumbs-item href="' . esc_url($link->getAttribute('href')) . '">' . esc_html($link->textContent) . '</gcds-breadcrumbs-item>';
                }
            }
            return $output . '</gcds-breadcrumbs>';
        }
    }
    $output = '<gcds-breadcrumbs slot="breadcrumb">';
    if (!is_home() && !is_front_page()) {
        $output .= '<gcds-breadcrumbs-item href="' . esc_url(home_url('/')) . '">' . esc_html__('Home') . '</gcds-breadcrumbs-item>';
        foreach (array_reverse(get_post_ancestors(get_queried_object_id())) as $id) {
            $output .= '<gcds-breadcrumbs-item href="' . esc_url(get_permalink($id)) . '">' . esc_html(get_the_title($id)) . '</gcds-breadcrumbs-item>';
        }
    }
    return $output . '</gcds-breadcrumbs>';
}

function cds_last_modified_date(): void
{
    if (get_the_ID()) {
        echo '<gcds-date-modified>' . esc_html(get_the_modified_date('Y-m-d')) . '</gcds-date-modified>';
    }
}

function cds_the_posts_navigation($args = []): void
{
    if ($GLOBALS['wp_query']->max_num_pages > 1) {
        $page = max(1, (int) get_query_var('paged'));
        $previous = $page > 1 ? get_pagenum_link($page - 1) : '';
        $next = $page < $GLOBALS['wp_query']->max_num_pages ? get_pagenum_link($page + 1) : '';
        echo '<gcds-pagination display="simple" label="' . esc_attr__('Posts navigation', 'cds-snc') . '" previous-href="' . esc_url($previous) . '" next-href="' . esc_url($next) . '" previous-label="' . esc_attr__('Newer posts', 'cds-snc') . '" next-label="' . esc_attr__('Older posts', 'cds-snc') . '"></gcds-pagination>';
    }
}

function cds_prev_next_links(): void
{
    $previous = get_previous_post();
    $next = get_next_post();
    if ($previous || $next) {
        echo '<gcds-pagination display="simple" label="' . esc_attr__('Document navigation', 'cds-snc') . '" previous-href="' . esc_url($previous ? get_permalink($previous) : '') . '" next-href="' . esc_url($next ? get_permalink($next) : '') . '" previous-label="' . esc_attr__('Previous blog post', 'cds-snc') . '" next-label="' . esc_attr__('Next blog post', 'cds-snc') . '"></gcds-pagination>';
    }
}
