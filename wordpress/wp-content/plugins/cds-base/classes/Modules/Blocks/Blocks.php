<?php

namespace CDS\Modules\Blocks;

class Blocks
{
    protected $version;

    public function __construct()
    {
        $this->version = "1.0.2";
        add_action('admin_enqueue_scripts', [$this, 'register'], 10, 2);

        add_action('admin_enqueue_scripts', [$this, 'editorStyles'], 20000, 2);

        add_action('wp_enqueue_scripts', [$this, 'publicStyles'], 99);

        add_action('admin_enqueue_scripts', [$this, 'editorAndPublicStyles'], 99);
        add_action('wp_enqueue_scripts', [$this, 'editorAndPublicStyles'], 99);

        /* Both of these change the _rendered_ output of latest-posts, but not the gutenberg editor output */
        add_filter(
            'render_block_core/latest-posts',
            ['CDS\Modules\Blocks\src\latestPosts\LatestPosts', 'renderBlock'],
            10,
            2
        );
        add_filter('excerpt_more', ['CDS\Modules\Blocks\src\latestPosts\LatestPosts', 'excerptMore']);
    }

    public function register()
    {
        /* blocks */
        register_block_type('cds-snc/expander', [
            'editor_script' => 'cds-snc',
        ]);

        register_block_type('cds-snc/alert', [
            'editor_script' => 'cds-snc',
        ]);

        register_block_type('cds-snc/accordion', [
            'editor_script' => 'cds-snc',
        ]);

        /* table styles */
        register_block_style('core/table', [
            'name' => 'bordered-table',
            'label' => 'Bordered Table',
        ]);

        register_block_style('core/table', [
            'name' => 'filterable',
            'label' => 'Filterable Table',
        ]);

        register_block_style('core/table', [
            'name' => 'responsive-cards',
            'label' => 'Responsive Cards Table',
        ]);

        if (function_exists('wp_set_script_translations')) {
            /**
             * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
             * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
             * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
             */
            wp_set_script_translations('cds-snc-base', 'cds-snc');
        }
    }

    public function editorStyles()
    {
        wp_enqueue_style(
            'cds-base-blocks-editor',
            plugin_dir_url(__FILE__) . 'src/styles/editor.css',
            [],
            $this->version,
        );

        wp_enqueue_style(
            'cds-base-editor-fonts-lato',
            'https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap',
            [],
            $this->version,
        );

        wp_enqueue_style(
            'cds-base-editor-fonts-noto',
            'https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap',
            [],
            $this->version,
        );
    }

    public function publicStyles()
    {
        wp_enqueue_style(
            'cds-base-blocks-public',
            plugin_dir_url(__FILE__) . 'src/styles/public.css',
            [],
            $this->version
        );
    }

    public function editorAndPublicStyles()
    {
        wp_enqueue_style(
            'cds-base-blocks-editor-public',
            plugin_dir_url(__FILE__) . 'src/styles/editor-and-public.css',
            [],
            $this->version
        );
    }
}
