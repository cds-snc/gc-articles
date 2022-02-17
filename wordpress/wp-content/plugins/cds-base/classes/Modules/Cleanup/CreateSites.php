<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class CreateSites
{
    public string $adminEmailMessage;
    public string $siteMessage;

    public function __construct()
    {
        $this->adminEmailMessage = __('Admin Email <strong>must be</strong> a current Super Admin', 'cds-snc');
        $this->siteMessage = __('or else a new Site won’t be created', 'cds-snc');

        // add message to the Add New Site Form
        add_action('network_site_new_form', [$this, 'editNewSiteForm']);
        // throw an error if a new user is attepted to be created when adding a new site
        add_action('pre_network_site_new_created_user', [$this, 'dieIfNewUser']);
    }

    public function editNewSiteForm(): void
    {
        // remove the existing message
        print "<style>.form-field:last-of-type {display: none;}</style>";
        printf(
            "<p>%s, %s.</p>",
            $this->adminEmailMessage,
            $this->siteMessage
        );
    }

    public function dieIfNewUser(): void
    {
        wp_die($this->adminEmailMessage . '.');
    }
}
