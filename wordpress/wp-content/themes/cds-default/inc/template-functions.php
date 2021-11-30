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
    $prev_id = false;
    $prev_post = get_previous_post();

    if ($prev_post && $prev_post->ID) {
        $prev_id = $prev_post->ID;
        $prev_permalink = get_permalink($prev_id);
    }

    $next_id = false;
    $next_post = get_next_post();

    if ($next_post && $next_post->ID) {
        $next_id = $next_post->ID;
        $next_permalink = get_permalink($next_id);
    } ?>

  <nav class="mrgn-tp-xl">
    <h2 class="wb-inv"> <?php _e('Document navigation', 'cds-snc'); ?> </h2>
    <ul class="pager">
      <?php if ($next_id && $next_permalink) : ?>
      <li class="next">
        <a id="<?php echo $next_id ?>" href="<?php echo $next_permalink; ?>">
            <?php _e(
                'Next blog post',
                'cds-snc'
            ); ?>
          &nbsp;»</a>
      </li>
      <?php endif; ?>

      <?php if ($prev_id && $prev_permalink) : ?>
      <li class="previous">
        <a id="<?php echo $prev_id ?>" href="<?php echo $prev_permalink; ?>"
           rel="prev">«&nbsp;<?php _e('Previous blog post', 'cds-snc'); ?></a>
      </li>
      <?php endif; ?>
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
        if (!empty($args['screen_reader_text']) && empty($args['aria_label'])) {
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

function custom_field_breadcrumb(): string
{
    global $wp_query;

    if (!$wp_query || !$wp_query->post) {
        return "";
    }

    $list_items = get_post_meta($wp_query->post->ID, 'breadcrumb', true);
    wp_reset_query();

    if (!$list_items) {
        return "";
    }

    $output = '<nav id="wb-bc" property="breadcrumb">';
    $output .= '<div class="container">';
    $output .= '<h2><?php _e("You are here:"); ?></h2>';
    $output .= '<ol class="breadcrumb">';
    $output .= $list_items;
    $output .= '</ol>';
    $output .= '</div>';
    $output .= '</nav>';

    return $output;
}

function cds_breadcrumb($sep = ''): string
{
    $breadcrumb = custom_field_breadcrumb();
    if ($breadcrumb !== "") {
        return $breadcrumb;
    }

    if (!function_exists('yoast_breadcrumb')) {
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

        $output = '<nav id="wb-bc" property="breadcrumb" aria-label="' .  __("Breadcrumbs") . '">';
        $output .= '<div class="container">';
        $output .= '<h2>' . __("You are here:") . '</h2>';
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

    try {
        $locale = get_locale();
        $pieces = explode("_", $locale);
        return $pieces[0];
    } catch (Exception $e) {
        return "en";
    }
}

function language_switcher_output($languages)
{
    $langs = [];

    try {
        foreach ($languages as $language) {
            $text = get_language_text($language['translated_name']);
            if (!$language['active']) {
                $link = '<a lang="' . $text['abbr'] . '" hreflang="' . $text['abbr'] . '" href="' . $language['url'] . '">';
                $link .= '<span class="hidden-xs">' . $text['full'] . '</span>';
                $link .= '<abbr title="' . $text['full'] . '" class="visible-xs h3 mrgn-tp-sm mrgn-bttm-0 text-uppercase">';
                $link .= $text['abbr'];
                $link .= '</abbr>';
                $link .= '</a>';

                $langs[] = $link;
            }
        }
    } catch (Exception $e) {
        error_log("language_switcher:" . $e->getMessage());
        //noop
    }

    return $langs;
}

function manual_language_switcher(): string
{
    global $wp_query;

    $output = "";

    if (!$wp_query || !$wp_query->post) {
        return $output;
    }

    $custom_language_switcher = get_post_meta($wp_query->post->ID, 'locale_switch_link', true);
    error_log("language_switcher:" . $custom_language_switcher);

    // format: {"active":false,"translated_name":"English","url":"/"}
    if ($custom_language_switcher) {
        $custom_language_switcher = json_decode($custom_language_switcher);
        $custom_language_switcher = (array)$custom_language_switcher;
        $output = language_switcher_output([$custom_language_switcher]);

        if (count($output) >= 1 && $output[0]) {
            return (string)$output[0];
        } else {
            error_log("language_switcher: failed to parse");
            $output = "";
        }
    }

    return $output;
}

function language_switcher(): string
{
    $output = manual_language_switcher();

    if ($output != "") {
        return $output;
    }

    if (function_exists('icl_get_languages')) {
        $languages = apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
        if ($languages && 1 < count($languages)) {
            $langs = language_switcher_output($languages);
            return join(', ', $langs);
        }
    }

    error_log("language_switcher: not found");
    return "";
}

/**
 * Function to print links for a wordpress menu. Used to create the Canada.ca footer.
 */
function get_footer_links(array $links): string
{
    $string = '';

    foreach ($links as $link) {
        $link = (object)$link; // cast to object so that we can use arrow notation
        $string .= sprintf(
            "<li><a href='%s'>%s</a></li>",
            esc_url($link->url),
            esc_html($link->title)
        );
    }

    return $string;
}

/**
 * Function that will return a HTML for a side nav if the current page meets the following conditions:
 *   - It is a "page"
 *   - It has 'child' pages OR it has a 'parent' page
 *
 * If the current page doesn't have a side nav, return an empty string
 */
function get_side_nav(): string
{
    global $post;
    $parent_page_is_current_page = '';

    $parents = get_post_ancestors($post->post_id);
    krsort($parents);
    $parents = array_merge(array(), $parents);

    if (is_home() || is_single()) {
        $id = get_option('page_for_posts');
        $parent = get_post_ancestors($id);
        $id = $parent[0];
    } elseif ($parents) {
        $id = $parents[0];
    } else {
        $id = $post->ID;
        $parent_page_is_current_page = "current_page_item";
    }

    $children = wp_list_pages('title_li=&child_of=' . $id . '&echo=0');
    $out = '';

    if ($children) {
        ob_start();
        ?>
            <div id="subpages" class="nav--about__desktop">
                <div class="nav--about__desktop__title <?php echo $parent_page_is_current_page; ?>"><?php echo get_the_title($id); ?></div>
                <nav class="nav--about" aria-label="Table of contents: <?php echo get_the_title($id); ?>">
                    <ul>
                        <?php echo $children; ?>
                    </ul>
                </nav>
            </div><!-- end of .nav--about__desktop -->
            <details class="nav--about__mobile">
                <summary><div><?php echo get_the_title($id); ?></div></summary>
                <nav class="nav--about" aria-label="Table of contents: <?php echo get_the_title($id); ?>">
                    <ul>
                        <?php echo $children; ?>
                    </ul>
                </nav>
            </details>
        <?php
        $out = ob_get_contents();
        ob_end_clean();
    }

    return $out;
}

/**
 * Function that returns the menu assigned to the "Primary" location
 * Also:
 *   - Add a "menu" button similar to bootstrap
 *   - Add aria-labels to the <nav> and <ul> submenu(s)
 *
 * If no menu is found, return an empty string
 */
function get_top_nav(): string
{
    // Don't get the menu by name, but by theme location. Returns false if not found
    $headerMenu = wp_nav_menu([
        "theme_location" => "header",
        "fallback_cb" => false,
        "echo" => false,
        "depth" => 2,
        "menu_class" => "nav nav--primary container",
        "container" => "nav",
        "container_class" => "nav--primary__container"
    ]);

    if ($headerMenu) {
        $topMenu = __('Top menu');
        $submenu = __('submenu');

        // Insert a button (markup taken from bootstrap)
        // It seems like we can't append an element using the PHP HTML Parser https://stackoverflow.com/q/51466367
        $headerMenu = str_replace('<nav class="nav--primary__container">', '<nav class="nav--primary__container"><div class="container"><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-top-of-the-page" aria-controls="menu-top-of-the-page" aria-expanded="false">Menu</button></div>', $headerMenu);

        $dom = new Dom();
        $dom->loadStr($headerMenu);

        // Insert aria-label for top nav (otherwise axe complains)
        $dom->find('.nav--primary__container')->setAttribute('aria-label', $topMenu);
        // // Insert aria-label for submenu
        $dom->find('.sub-menu')->setAttribute('aria-label', $submenu);

        return $dom->outerHTML;
    }

    return '';
}
