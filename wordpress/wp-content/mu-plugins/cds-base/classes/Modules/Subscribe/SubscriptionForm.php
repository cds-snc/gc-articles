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
        
        ob_start();
        $placeholder = "";
        if ( ! empty( $attributes['placeholderValue'] ) ) :
            $placeholder = $attributes['placeholderValue'];
        endif;
        ?>
        <div class="gc-form-wrapper">

           <form id="subscribe-form" method="POST" action="/wp-json/subscribe/v1/process">
                <?php wp_nonce_field('list_manager_nonce_action', 'list_manager'); ?>
                <div class="focus-group">
                    <label class="gc-label required" id="cds-email" for="email">
                        <?php _e("Enter your email:", "cds-snc"); ?>
                        <i class="visually-hidden"><?php _e("Required Field", "cds-snc"); ?></i>
                    </label>
                    <input class="gc-input-text" type="text" name="email" placeholder="<?php echo $placeholder; ?>" value=""/>
                </div>
                <div class="buttons">
                    <button class="gc-button gc-button" type="submit" id="subscribe-submit">
                        <?php _e("Subscribe", "cds-snc"); ?>
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
