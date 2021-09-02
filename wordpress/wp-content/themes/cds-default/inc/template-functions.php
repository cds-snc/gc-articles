<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package cds-default
 */

function cds_prev_next_links(): void
{
    $prev_post = get_previous_post();
    $prev_id = $prev_post->ID;
    $prev_permalink = get_permalink($prev_id);
    $next_post = get_next_post();
    $next_id = $next_post->ID;
    $next_permalink = get_permalink($next_id); ?>

    <nav class="mrgn-tp-xl">
        <h2 class="wb-inv"> <?php _e('Document navigation', 'cds-snc'); ?> </h2>
        <ul class="pager">
            <li class="next">
                <a id="<?php echo $prev_id ?>" href="<?php echo $next_permalink; ?>"><?php _e(
        'Next blog post',
        'cds-snc'
    ); ?> &nbsp;»</a>
            </li>
            <li class="previous">
                <a id="<?php echo $next_id ?>" href="<?php echo $prev_permalink; ?>"
                   rel="prev">«&nbsp;<?php _e('Previous blog post', 'cds-snc'); ?></a>
            </li>
        </ul>
    </nav>

    <?php
}

function cds_category_links($post_id, $separator = ','): string
{
    global $wp_rewrite;
    $categories = apply_filters('the_category_list', get_the_category($post_id), $post_id);

    $rel = is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ? 'rel="category tag"' : 'rel="category"';

    $list = '';

    $i = 0;
    /* https://wet-boew.github.io/GCWeb/templates/legislation/regulations-en.html */
    foreach ($categories as $category) {
        if (0 < $i) {
            $list .= $separator;
        }
        $list .= '<li><a href="' . get_category_link($category->term_id) . ' " class="' . $category->class . '" ' . $rel . '>[' . $category->name . ']</a></li>';
        ++$i;
    }

    return $list;
}

function cds_the_posts_navigation($args = []): void
{
    $navigation = '';

    // Don't print empty markup if there's only one page.
    if ($GLOBALS['wp_query']->max_num_pages > 1) {
        // Make sure the nav element has an aria-label attribute: fallback to the screen reader text.
        if (! empty($args['screen_reader_text']) && empty($args['aria_label'])) {
            $args['aria_label'] = $args['screen_reader_text'];
        }

        $args = wp_parse_args(
            $args,
            [
                'prev_text' => __('Older posts'),
                'next_text' => __('Newer posts'),
                'screen_reader_text' => __('Posts navigation'),
                'aria_label' => __('Posts'),
                'class' => 'posts-navigation',
            ]
        );

        $next_link = get_previous_posts_link($args['next_text']);
        $prev_link = get_next_posts_link($args['prev_text']);

        if ($prev_link) {
            $navigation .= '<li class="previous">' . $prev_link . '</li>';
        }

        if ($next_link) {
            $navigation .= '<li class="next">' . $next_link . '</li>';
        }

        $navigation = _navigation_markup($navigation, $args['class'], $args['screen_reader_text'], $args['aria_label']);
    }

    echo $navigation;
}

/* https://wet-boew.github.io/GCWeb/sites/breadcrumbs/breadcrumbs-en.html */

function cds_breadcrumb($sep = ''): string
{
    if (! function_exists('yoast_breadcrumb')) {
        return '';
    }

    try {
        $crumbs = yoast_breadcrumb('<div class="breadcrumbs">', '</div>', false);
        $dom = new Dom();
        $dom->loadStr($crumbs);
        $node = $dom->find('.breadcrumbs');
        $child = $node->firstChild();
        $html = $child->firstChild()->innerHtml;
        $parts = explode('|', $html);

        $output = '<nav id="wb-bc" property="breadcrumb">';
        $output .= '<div class="container">';
        $output .= '<h2><?php _e("You are here:"); ?></h2>';
        $output .= '<ol class="breadcrumb">';
        // note this will need to point to the correct language
        $output .= '<li><a href="https://www.canada.ca/en.html">Canada.ca</a></li>';
        foreach ($parts as $part) {
            $output .= '<li>';
            $output .= $part;
            $output .= '</li>';
        }

        $output .= '</ol>';
        $output .= '</div>';
        $output .= '</nav>';
        return $output;
    } catch (Exception $e) {
        return yoast_breadcrumb('<div class="breadcrumbs">', '</div>', false);
    }
}

function get_language_text($lang = ''): array
{
    if (strtolower($lang) === 'french' || strtolower($lang) === 'fr') {
        return ['full' => 'Français', 'abbr' => 'fr'];
    }

    return ['full' => 'English', 'abbr' => 'en'];
}

function get_active_language(): string
{
    if (function_exists('icl_get_languages')) {
        if (ICL_LANGUAGE_CODE !== null) {
            return ICL_LANGUAGE_CODE;
        }
    }

    return 'en';
}

function language_switcher()
{
    if (function_exists('icl_get_languages')) {
        $languages = apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
        if (1 < count($languages)) {
            foreach ($languages as $language) {
                $text = get_language_text($language['translated_name']);
                if (! $language['active']) {
                    $link = '<a lang="' . $text['abbr'] . '" hreflang="' . $text['abbr'] . '" href="' . $language['url'] . '">';
                    $link .= '<span class="hidden-xs">' . $text['full'] . '</span>';
                    $link .= '<abbr title="' . $text['full'] . '" class="visible-xs h3 mrgn-tp-sm mrgn-bttm-0 text-uppercase">';
                    $link .= $text['abbr'];
                    $link .= '</abbr>';
                    $link .= '</a>';

                    $langs[] = $link;
                }
            }
            return join(', ', $langs);
        }
    }
}
