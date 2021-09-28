<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class SitesToCollections
{
    public function __construct()
    {
        if (is_admin()) {
            // note we may run into performance issues here so limiting to admin interface only
            add_filter('gettext', [$this, 'changeSitesLabel'], 10, 3);
        }

        // for non wp-admin replace only in Admin Bar
        add_action('wp_before_admin_bar_render', [$this, 'changeSitesLabelAdminBar'], 99, 0);
    }

    public function changeSitesLabel($translation, $text, $domain): string
    {
        if ($domain !== "default") {
            // only look for default wp translations
            return $translation;
        }

        return str_ireplace('Site', "Collection", $text);
    }

    public function changeSitesLabelAdminBar(): void
    {
        global $wp_admin_bar;
        $my_sites = $wp_admin_bar->get_node('my-sites');

        $wp_admin_bar->add_node([
            'id' => 'my-sites',
            'title' => "My Collections"
        ]);
    }
}
