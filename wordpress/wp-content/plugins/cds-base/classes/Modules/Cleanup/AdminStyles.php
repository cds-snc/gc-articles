<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class AdminStyles
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles'], 99);
        add_action('admin_head', [$this, 'gutenbergIconCSS'], 99);
    }

    public function enqueueStyles()
    {
        // add stylesheet to the wp admin
        wp_enqueue_style(
            'cds-base-style-admin',
            plugin_dir_url(__FILE__) . 'css/admin.css',
            ["wp-edit-blocks"],
            BASE_PLUGIN_NAME_VERSION,
        );

        wp_enqueue_style(
            'cds-base-style-admin-wet',
            plugin_dir_url(__FILE__) . 'css/admin-wet.css',
            [],
            BASE_PLUGIN_NAME_VERSION,
        );
    }

    public function gutenbergIconCSS()
    {
        ob_start();
        ?>
        <style>
            .edit-post-fullscreen-mode-close.has-icon svg,
            .edit-post-fullscreen-mode-close.has-icon img {
                display:none;
            }

            .edit-post-fullscreen-mode-close.has-icon:before {
                display: inline-block;
            }
        </style>
        <?php

        $show_maple_leaf_css = ob_get_clean();
        ob_end_clean();

        $hasIcon = get_site_icon_url() !== "";

        if (!$hasIcon) {
            // If there is an icon, show the default maple leaf and hide the WP logo
            echo $show_maple_leaf_css;
        }
    }
}
