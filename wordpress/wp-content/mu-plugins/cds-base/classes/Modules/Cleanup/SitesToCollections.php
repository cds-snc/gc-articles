<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class SitesToCollections
{
    public string $adminEmailMessage;
    public string $collectionMessage;

    public function __construct()
    {
        if (is_admin()) {
            // note we may run into performance issues here so limiting to admin interface only
            add_filter('gettext', [$this, 'changeSitesLabel'], 10, 3);
        }

        // for non wp-admin replace only in Admin Bar
        add_action('wp_before_admin_bar_render', [$this, 'changeSitesLabelAdminBar'], 99, 0);

        $this->adminEmailMessage = __('Admin Email <strong>must be</strong> a current Super Admin', 'cds-snc');
        $this->collectionMessage = __('or else a Collection won’t be created', 'cds-snc');

        // add message to the Add New Collection Form
        add_action('network_site_new_form', [$this, 'editNewCollectionForm']);
        // throw an error if a new user is attepted to be created when adding a New Collection
        add_action('pre_network_site_new_created_user', [$this, 'dieIfNewUser']);
    }

    public function changeSitesLabel($translation, $text, $domain): string
    {
        if ($domain !== "default") {
            // only look for default wp translations
            return $translation;
        }

        if (str_contains($translation, '###')) {
            return $translation;
        }

        return str_ireplace('Site', "Collection", $text);
    }

    public function changeSitesLabelAdminBar(): void
    {
        if (is_super_admin()) {
            return;
        }

        // remove "My Sites"
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('my-sites');
    }

    public function editNewCollectionForm(): void
    {
        // remove the existing message
        print "<style>.form-field:last-of-type {display: none;}</style>";
        printf(
            "<p>%s, %s.</p>",
            $this->adminEmailMessage,
            $this->collectionMessage
        );
    }

    public function dieIfNewUser(): void
    {
        wp_die($this->adminEmailMessage . '.');
    }
}
