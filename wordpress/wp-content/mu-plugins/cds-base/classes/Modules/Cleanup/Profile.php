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
        //$crawler->filter('.user-language-wrap')->remove();

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

        /*--------------------------------------------*
         * Remove Application Passwords Fields
         *--------------------------------------------*/
        $crawler->filter('.application-passwords')->remove();

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

    public function wpmlOptions($userId): void
    {
        echo '<input type="hidden" id="icl_show_hidden_languages" name="icl_show_hidden_languages" type="checkbox" value="1">';
    }
}
