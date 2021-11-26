<?php

namespace CDS\Modules\Cleanup;

class Media
{

    public function __construct()
    {
        add_action('admin_init', [$this, 'removeListMode'], 99);
        add_action('admin_head', [$this, 'removeListModeIcon'], 99);
    }

    public function removeListMode()
    {
        // https://wordpress.stackexchange.com/questions/181307/how-to-remove-list-view-from-media-library

        if (isset($_GET['mode']) && $_GET['mode'] !== 'grid') {
            wp_redirect(admin_url('upload.php?mode=grid'));
            exit;
        } else {
            //required by upload.php, handle the case if user just navigates to...
            //http://www.example.com/wp-admin/upload.php (with no mode query argument)
            $_GET['mode'] = 'grid';
        }
    }

    // note hiding both icons causes alignment issues
    // keeping the grid icon but it's a no-op
    public function removeListModeIcon()
    {
        ?>
        <style type="text/css">
        .wp-filter .view-switch {
            margin: 28px 12px 0 -15px !important;
        }

        .view-switch .view-grid {
            display: none;
        }
        </style>
        <?php
    }
}

add_action('admin_head', function () {
    ?>
    <style type="text/css">
        .view-switch .view-list {
            display: none;
        }
    <style>
    <?php
});