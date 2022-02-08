<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use Wa72\HtmlPageDom\HtmlPage;

class Profile
{
    public function __construct()
    {
        add_action('personal_options', [$this, 'start']);
        add_filter('additional_capabilities_display', [$this, 'removeAdditionalCapabilitiesFunc']);
        add_action('wpml_user_profile_options', [$this, 'wpmlOptions']);
        add_action('additional_capabilities_display', [$this, 'yoastOptions']);

        if (is_admin()) {
            remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
            remove_action('personal_options', 'wpml_show_user_options');
            remove_action('personal_options_update', ['SitePress', 'save_user_options']);
        }
    }

    /**
     * Removes Profile section
     */
    public static function stop(): void
    {
        $html = ob_get_contents();
        ob_end_clean();

        // note suppressing warning here: libxml_disable_entity_loader() deprecated
        // see https://github.com/wasinger/htmlpagedom/issues/35
        $crawler = @new HtmlPage($html);

        // add IDs to headings
        $headings = $crawler->filter('h2')->reduce(
            static function ($node, $j) {
                $remove = ['Personal Options', 'Name', 'Contact Info', 'About Yourself', 'Yoast SEO settings'];
                $id     = strtolower(str_replace(' ', '_', $node->html()));

                if (in_array($node->html(), $remove)) {
                    $node->setAttribute('id', $id);

                    return true;
                }

                return false;
            }
        );

        /*--------------------------------------------*
         * Remove Personal Options (fields)
         *--------------------------------------------*/
        // WPML settings under personal options
        $crawler->filter('#name')->addClass('hidden');
        $crawler->filter('.user-language-wrap')->remove();

        $crawler->filter('.user-user-login-wrap')->remove();
        // note nickname is a require field so it's hidden using CSS
        $crawler->filter('.user-display-name-wrap')->remove();

        /*--------------------------------------------*
         * Remove About Yourself
         *--------------------------------------------*/
        $crawler->filter('#about_yourself')->remove();
        $crawler->filter('.user-description-wrap')->remove();
        $crawler->filter('.user-profile-picture')->remove();

        /*--------------------------------------------*
         * Remove Contact Info Fields
         *--------------------------------------------*/
        $contact_info = [
            'url',
            'aim',
            'yim',
            'jabber',
            'facebook',
            'instagram',
            'linkedin',
            'myspace',
            'pinterest',
            'soundcloud',
            'tumblr',
            'twitter',
            'youtube',
            'wikipedia',
        ];

        foreach ($contact_info as $contact) {
            $crawler->filter('.user-' . $contact . '-wrap')->remove();
        }

        /*--------------------------------------------*
         * Remove Yoast Settings
         *--------------------------------------------*/
        $crawler->filter('.yoast-settings')->remove();
        $crawler->filter('#yoast-seo-schema ~ p')->remove();
        $crawler->filter('#yoast-seo-schema')->remove();

        /*--------------------------------------------*
         * Remove Application Passwords Fields
         *--------------------------------------------*/
        $crawler->filter('.application-passwords')->remove();

        /*--------------------------------------------*
         * Remove "Log Out Everywhere Else" button
         *--------------------------------------------*/
        $crawler->filter('.user-sessions-wrap')->remove();

        /*--------------------------------------------*
         * Remove Yubikeys from the 2FA plugin
         *--------------------------------------------*/
        // Remove the last row of the 2FA table, which has the option to add Yubikeys
        $crawler->filter('.two-factor-methods-table tbody tr:last-of-type')->remove();
        // Remove the section about security keys
        $crawler->filter('.security-keys')->remove();


        echo $crawler->save();
    }

    /**
     * Called on 'personal_options'.
     */
    public function start(): void
    {
        if (is_super_admin()) {
            return;
        }
        $action = (IS_PROFILE_PAGE ? 'show' : 'edit') . '_user_profile';
        add_action($action, [$this, 'stop']);
        ob_start();
    }

    public function removeAdditionalCapabilitiesFunc(): bool
    {
        return false;
    }

    public function yoastOptions(): void
    {
        // re-add via hidden fields -- with no values
        // :( we need these values in order to submit without yoast errors

        $fields = ["honorificPrefix",
        "honorificSuffix",
        "birthDate",
        "gender",
        "award",
        "knowsAbout",
        "knowsLanguage",
        "jobTitle",
        "worksFor"];

        foreach ($fields as $field) {
            printf('<input type="hidden" name="wpseo_user_schema[%s]">', $field);
        }
    }

    public function wpmlOptions($userId): void
    {
        echo '<input type="hidden" id="icl_admin_language_for_edit" name="icl_admin_language_for_edit" value="0">';
        echo '<input type="hidden" id="icl_show_hidden_languages" name="icl_show_hidden_languages" value="1">';
        echo '<input type="hidden" id="icl_user_admin_language" name="icl_user_admin_language" value="en">';
    }
}
