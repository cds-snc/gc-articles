<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class CreateSites
{
    public function __construct()
    {
        // add message to the Add New Site Form
        add_action('network_site_new_form', [$this, 'editNewSiteForm']);
        // throw an error if a new user is attepted to be created when adding a new site
        add_action('pre_network_site_new_created_user', [$this, 'dieIfNewUser']);
    }

    public function editNewSiteForm(): void
    {
        $adminEmailMessage = __('Admin Email <strong>must be</strong> a current Super Admin', 'cds-snc');
        $siteMessage = __('or else a new Site won’t be created', 'cds-snc');

        // remove the existing message
        print "<style>.form-field:last-of-type {display: none;}</style>";
        printf(
            "<p>%s, %s.</p>",
            $adminEmailMessage,
            $siteMessage
        );
    }

    public function dieIfNewUser(): void
    {
        $adminEmailMessage = __('Admin Email <strong>must be</strong> a current Super Admin', 'cds-snc');

        wp_die($adminEmailMessage . '.');
    }
}
