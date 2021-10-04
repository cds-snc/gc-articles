<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Notices
{
    public function __construct()
    {
        add_action('in_admin_header', static function (): void {
            /**
             * Note: commenting these out for now. This ended up having unintended side effect of disabling all
             * admin_notices, and should be more narrowly focussed on hiding specific notices.
             */
            // remove_all_actions('admin_notices');
            // remove_all_actions('all_admin_notices');

            // if we want to add a custom notice later
            /*
            add_action('admin_notices', function () {
                echo 'My notice';
            });
            */

        }, 1000);
    }
}
