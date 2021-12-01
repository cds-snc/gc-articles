<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/inc/template-functions.php';
require_once __DIR__ . '/inc/template-filters.php';

/**
 * cds-default functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cds-default
 */

// phpcs:disable
if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '2.5.1');
}

if (!defined('THEME_NAMESPACE')) {
    // Replace the version number of the theme on each release.
    define('THEME_NAMESPACE', 'cds-snc');
}
// phpcs:enable

if (!function_exists('cds_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */

    // phpcs:disable
     function load_translations(): void
    {
        $loaded = load_theme_textdomain(
        'cds-snc',
        get_template_directory() . '/languages',
       );
    }
    // phpcs:enable

    function cds_setup(): void
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on cds-default, use a find and replace
         * to change 'cds-snc' to the name of your theme in all the template files.
         */
        load_translations();

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus([
            'header' => esc_html__('Primary', 'cds-snc'),
            'side-left' => esc_html__('Secondary', 'cds-snc'),
            'footer' => esc_html__('Footer', 'cds-snc'),
        ]);

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        add_theme_support('editor-color-palette', array(
            array(
                'name'  => __('Black', 'cds-snc'),
                'slug'  => 'black',
                'color' => '#000000',
            ),
            array(
                'name'  => __('White', 'cds-snc'),
                'slug'  => 'white',
                'color' => '#ffffff',
            ),
            array(
                'name'  => __('Primary Blue', 'cds-snc'),
                'slug'  => 'primary-blue',
                'color' => '#284162',
            ),
            array(
                'name'  => __('Dark Blue', 'cds-snc'),
                'slug'  => 'dark-blue',
                'color' => '#32373c',
            ),
            array(
                'name'  => __('Light Blue', 'cds-snc'),
                'slug'  => 'light-blue',
                'color' => '#b2e3ff',
            ),
            array(
                'name'  => __('Dark Grey', 'cds-snc'),
                'slug'  => 'dark-grey',
                'color' => '#444444',
            ),
            array(
                'name'  => __('Grey', 'cds-snc'),
                'slug'  => 'grey',
                'color' => '#bfc1c3',
            ),
            array(
                'name'  => __('Orange', 'cds-snc'),
                'slug'  => 'orange',
                'color' => '#ffbf47',
            ),
            array(
                'name'  => __('Green', 'cds-snc'),
                'slug'  => 'green',
                'color' => '#00703c',
            ),
            array(
                'name'  => __('Red', 'cds-snc'),
                'slug'  => 'red',
                'color' => '#af3c43',
            ),
        ));
    }
}
add_action('after_setup_theme', 'cds_setup');

/**
 * Enqueue scripts and styles.
 */
function cds_scripts(): void
{
    wp_enqueue_style('cds-style', get_stylesheet_uri(), [], _S_VERSION);

    wp_enqueue_script('cds-main', get_template_directory_uri() . '/js/main.js', ['jquery'], _S_VERSION, true);
}

add_action('wp_enqueue_scripts', 'cds_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Remove the "home" body class (conflicts with WET), replace with "homepage"
 */
add_filter('body_class', function (array $classes) {
    if (in_array('home', $classes)) {
        unset($classes[array_search('home', $classes)]);
        $classes[] = 'homepage';
    }

    return $classes;
});

add_action('init', function () {
    remove_theme_support('core-block-patterns');
    unregister_block_pattern_category('buttons');
    unregister_block_pattern_category('columns');
    unregister_block_pattern_category('gallery');
    unregister_block_pattern_category('header');
    unregister_block_pattern_category('text');
    unregister_block_pattern_category('query');
});

if (! function_exists('cds_register_block_patterns')) :
    function cds_register_block_patterns()
    {

        if (! ( function_exists('register_block_pattern_category') && function_exists('register_block_pattern') )) {
            return;
        }

        // The block pattern categories
        $cds_block_pattern_categories = apply_filters('cds_block_pattern_categories', array(
            'homepage' => array(
                'label'         => esc_html__('GC Homepage', 'cds-snc'),
            )
        ));

        // Sort the block pattern categories alphabetically based on the label value, to ensure alphabetized order when the strings are localized.
        uasort($cds_block_pattern_categories, function ($a, $b) {
            return strcmp($a["label"], $b["label"]);
        });

        // Register block pattern categories.
        foreach ($cds_block_pattern_categories as $slug => $settings) {
            register_block_pattern_category($slug, $settings);
        }

        // patterns
        $cds_block_patterns = apply_filters('cds_block_patterns', array(
            'cds/homepage' => array(
                'title'         => esc_html__('Default homepage', 'cds-snc'),
                'categories'    => array( 'homepage' ),
                'content'       => cds_get_block_pattern_markup('homepage/landing'),
            ),
        ));

        // Register block patterns.
        foreach ($cds_block_patterns as $slug => $settings) {
            register_block_pattern($slug, $settings);
        }
    }
    add_action('after_setup_theme', 'cds_register_block_patterns');
endif;

if (! function_exists('cds_get_block_pattern_markup')) :
    function cds_get_block_pattern_markup($pattern_name)
    {

        $path = 'inc/block-patterns/' . $pattern_name . '.php';

        if (! locate_template($path)) {
            return;
        }

        ob_start();
        include(locate_template($path));
        return ob_get_clean();
    }
endif;

add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');
