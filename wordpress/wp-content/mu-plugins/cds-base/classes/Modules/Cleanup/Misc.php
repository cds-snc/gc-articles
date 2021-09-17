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
}