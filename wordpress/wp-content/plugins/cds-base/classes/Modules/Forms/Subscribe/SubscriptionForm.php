<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

use CDS\Modules\Forms\Utils;

class SubscriptionForm
{
    public static function register()
    {
        $instance = new self();
        add_action('init', [$instance, 'registerShortCode']);
    }

    public function registerShortCode()
    {
        add_shortcode('subscribe', [$this, 'render']);
    }

    public function render($attributes = []): string
    {
        $listId = "";
        $emailLabel = __("Enter your email:", "cds-snc");
        $subscribeLabel = __("Subscribe", "cds-snc");
        $privacyLink = "";

        if (!empty($attributes['listId'])) :
            $listId = $attributes['listId'];
        endif;

        if (!empty($attributes['emailLabel'])) :
            $emailLabel = $attributes['emailLabel'];
        endif;

        if (!empty($attributes['subscribeLabel'])) :
            $subscribeLabel = $attributes['subscribeLabel'];
        endif;

        if (!empty($attributes['privacyLink'])) :
            $privacyLink = $attributes['privacyLink'];
        endif;

        $apiEndpoint = site_url() . '/wp-json/subscribe/v1/process';

        $missingValuesText = [
            'NOTIFY_API_KEY' => __('Notify API key', 'cds-snc'),
            'NOTIFY_SUBSCRIBE_TEMPLATE_ID' => __('Subscription template ID', 'cds-snc')
        ];

        $settingsUrl = admin_url("/admin.php?page=settings");

        $listsUrl = admin_url("/admin.php?page=gc-lists_subscribers#/lists");

        $missingText = __('You must configure your %s. Visit <a href="%s">%s</a>.', 'cds-snc');

        if (!$listId) {
            if (is_user_logged_in()) {
                return __("You must select a list from '<strong>Block</strong>' settings 👉", "cds-snc");
            }
            return "<!-- error no list selected -->";
        }

        $apiKey = get_option("NOTIFY_API_KEY");

        if (!$apiKey) {
            if (is_user_logged_in()) {
                return sprintf($missingText, $missingValuesText["NOTIFY_API_KEY"], $settingsUrl, $settingsUrl);
            }
            return "<!-- error notify api key -->";
        }

        $subscribeTemplate = get_option("NOTIFY_SUBSCRIBE_TEMPLATE_ID");

        if (!$subscribeTemplate) {
            if (is_user_logged_in()) {
                return sprintf($missingText, $missingValuesText["NOTIFY_SUBSCRIBE_TEMPLATE_ID"], $settingsUrl, $settingsUrl);
            }
            return "<!-- error subscribe template id -->";
        }

        ob_start();
        ?>
        <div class="gc-form-wrapper">
           <form id="cds-form" method="POST" action="<?php echo $apiEndpoint; ?>">
                <input type="hidden" name="list_id" value="<?php echo $listId; ?>"/>

            <?php
                wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                );

                Utils::textField(id: 'email', label: $emailLabel);
                Utils::submitButton($subscribeLabel);
            ?>
            </form>

            <?php
            if ($privacyLink) {
                $policyLinkText = __('Privacy Policy', 'cds-snc');
                printf('<p><a href="%s">%s</a></p>', $privacyLink, $policyLinkText);
            }
            ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
