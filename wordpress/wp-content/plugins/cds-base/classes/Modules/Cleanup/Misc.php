<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Misc
{
    public function __construct()
    {
        add_filter('admin_footer_text', '__return_false');
        add_filter('screen_options_show_screen', [$this, 'removeScreenOptions']);
        add_action('admin_head', [$this, 'removeHelpTab']);

        add_filter('post_row_actions', [$this, 'removeQuickEdit'], 10, 1);
        add_filter('page_row_actions', [$this, 'removeQuickEdit'], 10, 1);

        add_filter('user_row_actions', [$this, 'removeView'], 10, 1);

        add_filter("manage_edit-page_columns", [$this,"removeCommentsColumn"]);
        add_filter("manage_edit-post_columns", [$this,"removeCommentsColumn"]);
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

    public function removeView($actions)
    {
        unset($actions['view']);
        return $actions;
    }

    public function removeCommentsColumn($columns)
    {
        unset($columns['comments']);
        return $columns;
    }
}
