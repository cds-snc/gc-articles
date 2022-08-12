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
        $placeholder = "";
        $listId = "";
        $emailLabel = __("Enter your email:", "cds-snc");
        $subscribeLabel = __("Subscribe", "cds-snc");
        $privacyLink = "";

        if (!empty($attributes['placeholderValue'])) :
            $placeholder = $attributes['placeholderValue'];
        endif;

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
            'NOTIFY_API_KEY' => __('Notify API key', 'gc-lists'),
            'NOTIFY_GENERIC_TEMPLATE_ID' => __('a list'),
            'NOTIFY_SUBSCRIBE_TEMPLATE_ID' => __('Subscription template ID')
        ];

        $settingsUrl = admin_url("/wp-admin/admin.php?page=settings");
        $missingText = __('You must configure your %s visit <a href="%s">Notify API Settings</a>.', 'gc-lists');

        if (!$listId) {
            if (is_user_logged_in()) {
                return __("No list selected", "cds-snc");
            }
            return "<!-- error no list selected -->";
        }

        $apiKey = get_option("NOTIFY_API_KEY");

        if (!$apiKey) {
            if (is_user_logged_in()) {
                return sprintf($missingText,$missingValuesText["NOTIFY_API_KEY"], $settingsUrl);
            }
            return "<!-- error api key -->";
        }

        $subscribeTemplate = get_option("NOTIFY_API_KEY");

        if (!$subscribeTemplate) {
            if (is_user_logged_in()) {
                return sprintf($missingText,$missingValuesText["NOTIFY_SUBSCRIBE_TEMPLATE_ID"], $settingsUrl);
            }
            return "<!-- error subscribe template -->";
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

                Utils::textField(id: 'email', label: $emailLabel, placeholder: $placeholder);
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
