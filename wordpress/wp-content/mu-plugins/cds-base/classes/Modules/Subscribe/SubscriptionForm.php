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

    public function render($atts, $content = null): string
    {
        ob_start();
        ?>
        <form id="subscribe-form" method="POST" action="/wp-json/subscribe/v1/process">
            <?php wp_nonce_field('list_manager_nonce_action', 'list_manager'); ?>
            <p>
                <label>
                    <?php _e("Enter your email:", "cds-snc"); ?>
                    <input type="text" name="email" value=""/>
                </label>
            </p>
            <button type="submit" id="subscribe-submit"><?php _e("Subscribe", "cds-snc"); ?></button>
        </form>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
