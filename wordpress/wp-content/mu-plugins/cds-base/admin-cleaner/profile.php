<?php

declare(strict_types=1);

use Wa72\HtmlPageDom\HtmlPage;

add_action('personal_options', ['ProfileCleaner', 'start']);

add_filter('additional_capabilities_display', 'remove_additional_capabilities_func');

function remove_additional_capabilities_func(): bool
{
    return false;
}

add_action('wpml_user_profile_options', ['ProfileCleaner', 'wpml_options']);

class ProfileCleaner
{
    /**
     * Utility method to search text within a string
     */
    public static function contains($haystack, $needle): bool
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Called on 'personal_options'.
     */
    public static function start(): void
    {
        if (is_super_admin()) {
            return;
        }
        $action = (IS_PROFILE_PAGE ? 'show' : 'edit') . '_user_profile';
        add_action($action, [self::class, 'stop']);
        ob_start();
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
                $id = strtolower(str_replace(' ', '_', $node->html()));

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
        * Two-Factor Options
        *--------------------------------------------*/
        $rows = $crawler->filter('.two-factor-methods-table tbody tr')->reduce(
            static function ($node, $j) {
                if (ProfileCleaner::contains($node->html(), 'Email')) {
                    return true;
                }

                if (ProfileCleaner::contains($node->html(), 'Backup Verification Codes')) {
                    return true;
                }

                if (ProfileCleaner::contains($node->html(), 'Dummy Method')) {
                    return true;
                }

                return false;
            }
        );

        $rows->remove();

        echo $crawler->save();
    }

    public static function wpml_options($userId): void
    {
        echo '<input type="hidden" id="icl_show_hidden_languages" name="icl_show_hidden_languages" type="checkbox" value="1">';
    }
}

if (is_admin()) {
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    remove_action('personal_options', 'wpml_show_user_options');
    remove_action('personal_options_update', ['SitePress','save_user_options']);
}
