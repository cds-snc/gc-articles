<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

use CDS\Modules\Forms\Utils;

class SubscriptionForm
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        add_shortcode('subscribe', [$this, 'render']);
    }

    public function render($attributes = []): string
    {
        $placeholder = "";
        $listId = "";
        $emailLabel = __("Enter your email:", "cds-snc");
        $subscribeLabel = __("Subscribe", "cds-snc");

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

        $apiEndpoint = site_url() . '/wp-json/subscribe/v1/process';

        ob_start();
        ?>
        <div class="gc-form-wrapper">
           <form id="cds-form" method="POST" action="<?php echo $apiEndpoint; ?>">
                <input type="hidden" name="list_id" value="<?php echo $listId; ?>"/>

                <?php wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                ); ?>

                <?php echo Utils::textField('email', $emailLabel, null, null, $placeholder); ?>

                <div class="buttons">
                    <button class="gc-button gc-button" type="submit" id="submit">
                        <?php echo $subscribeLabel ; ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
