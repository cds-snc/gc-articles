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

        add_action('edit_user_profile', [$this, 'displayUnfilteredHTMLMeta']);
        add_action('edit_user_profile_update', [$this,'updateUnfilteredHTMLMeta']);

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

        $crawler = @new HtmlPage($html);

        // add IDs to headings
        $headings = $crawler->filter('h2')->reduce(
            static function ($node, $j) {
                $remove = ['Personal Options', 'Name', 'Contact Info', 'About Yourself'];
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
         * Remove Application Passwords Fields
         *--------------------------------------------*/
        $crawler->filter('.application-passwords')->remove();

        /*--------------------------------------------*
         * Remove "Log Out Everywhere Else" button
         *--------------------------------------------*/
        $crawler->filter('.user-sessions-wrap')->remove();

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
        echo '<input type="hidden" id="icl_admin_language_for_edit" name="icl_admin_language_for_edit" value="0">';
        echo '<input type="hidden" id="icl_show_hidden_languages" name="icl_show_hidden_languages" value="1">';
        echo '<input type="hidden" id="icl_user_admin_language" name="icl_user_admin_language" value="en">';
    }

    public function displayUnfilteredHTMLMeta($user)
    {
        if (!is_super_admin()) {
            return;
        }

        printf('<h3>%s</h3>', __('Unsafe HTML permissions', 'cds-snc'));
        print "<table class='form-table'>";

        print '<tr>';
        $allow_unfiltered_html = user_can($user, "allow_unfiltered_html");

        if ($allow_unfiltered_html) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        printf(
            "<th><label for='allow_unfiltered_html'>%s</label></th>",
            __('Unsafe HTML', 'cds-snc'),
        );
        printf(
            "<td><input value='true' type='checkbox' name='allow_unfiltered_html' id='allow_unfiltered_html' %s /> %s</td>",
            $checked,
            __('Allow iFrames', 'cds-snc'),
        );

        print '</tr>';
        print '</table>';
    }

    public function updateUnfilteredHTMLMeta($userId)
    {
        if (!is_super_admin()) {
            return;
        }

        $user = get_user_by('id', $userId);
        // these have to both be set for the 'unfiltered html' permission to work
        $user->remove_cap('unfiltered_html');
        $user->remove_cap('allow_unfiltered_html');

        if (isset($_POST['allow_unfiltered_html']) && $_POST['allow_unfiltered_html'] === "true") {
            $user->add_cap('unfiltered_html');
            $user->add_cap('allow_unfiltered_html');
        }
    }
}
