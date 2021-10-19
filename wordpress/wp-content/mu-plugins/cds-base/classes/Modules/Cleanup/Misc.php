<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use CDS\Modules\Cleanup\PostTable;

class Misc
{
    public function __construct()
    {
        add_filter('admin_footer_text', '__return_false');
        add_filter('screen_options_show_screen', [$this, 'removeScreenOptions']);
        add_action('admin_head', [$this, 'removeHelpTab']);

        add_filter('post_row_actions', [$this, 'removeQuickEdit'], 10, 1);
        add_filter('page_row_actions', [$this, 'removeQuickEdit'], 10, 1);

        add_filter('views_edit-post', [$this, "customPostTable"]);
    }

    public function removeScreenOptions()
    {
        return false;
    }

    public function removeHelpTab(): void
    {
        $screen = get_current_screen();
        $screen->remove_help_tabs();
    }

    public function removeQuickEdit($actions)
    {
        unset($actions['inline hide-if-no-js']);
        return $actions;
    }

    public function customPostTable($views)
    {
        global $wp_list_table;
        $wp_list_table = new PostTable();
        return $views;
    }
}
