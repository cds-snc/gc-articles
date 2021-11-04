<?php

declare(strict_types=1);

namespace CDS\Modules\Subscribe;

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
        $emailLabel = _("Enter your email:", "cds-snc");
        $subscribeLabel = _("Subscribe", "cds-snc");

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

        ob_start();
        ?>
        <div class="gc-form-wrapper">
           <form id="subscribe-form" method="POST" action="/wp-json/subscribe/v1/process">
                <input type="hidden" name="list_id" value="<?php echo $listId; ?>"/>
                <?php wp_nonce_field('list_manager_nonce_action', 'list_manager'); ?>
                <div class="focus-group">
                    <label class="gc-label required" id="cds-email" for="email">
                        <?php echo $emailLabel; ?>
                        <i class="visually-hidden"><?php _e("Required Field", "cds-snc"); ?></i>
                    </label>
                    <input class="gc-input-text" type="text" name="email" placeholder="<?php echo $placeholder; ?>" value=""/>
                </div>
                <div class="buttons">
                    <button class="gc-button gc-button" type="submit" id="subscribe-submit">
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
