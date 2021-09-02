<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/template-functions.php';
require_once __DIR__ . '/inc/template-filters.php';

/**
 * cds-default functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cds-default
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

if (!defined('THEME_NAMESPACE')) {
    // Replace the version number of the theme on each release.
    define('THEME_NAMESPACE', 'cds-snc');
}

if (!function_exists('cds_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function load_translations(): void
    {
        $domain = 'cds-snc';
        $locale = apply_filters('theme_locale', determine_locale(), $domain);
        $mo = $domain . '-' . $locale . '.mo';
        load_textdomain(
            $domain,
            get_template_directory() . '/languages/' . $mo,
        );
    }

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
            'menu-1' => esc_html__('Primary', 'cds-snc'),
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
    }
}
add_action('after_setup_theme', 'cds_setup');

/**
 * Enqueue scripts and styles.
 */
function cds_scripts(): void
{
    wp_enqueue_style('cds-style', get_stylesheet_uri(), [], _S_VERSION);
}

add_action('wp_enqueue_scripts', 'cds_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
