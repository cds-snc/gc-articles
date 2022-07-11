<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

class ListManagerSettings
{
    protected string $admin_page = 'cds_notify_send';

    public static function register()
    {
        add_filter(
            'option_page_capability_list_manager_settings_option_group',
            function ($capability) {
                return 'manage_list_manager';
            },
        );
    }
}
