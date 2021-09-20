<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Notices
{
    public function __construct()
    {
        add_action('in_admin_header', static function (): void {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');

            // if we want to add a custom notice later
            /*
            add_action('admin_notices', function () {
                echo 'My notice';
            });
            */
        }, 1000);
    }
}
