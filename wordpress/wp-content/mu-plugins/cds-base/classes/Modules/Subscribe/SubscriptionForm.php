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
        <div class="gc-form-wrapper">
            <!--
            <div class="gc-alert gc-alert--error gc-alert--validation" data-testid="alert" id="gc-form-errors" tabindex="0" role="alert">
                <div class="gc-alert__body">
                    <h2 class="gc-h3">Please correct the errors on the page</h2>
                    <ol class="gc-ordered-list">
                        <li>
                            <a href="#2" class="gc-error-link">Please complete the required field to continue</a>
                        </li>
                    </ol>
                </div>
            </div>
            -->

            <form id="subscribe-form" method="POST" action="/wp-json/subscribe/v1/process">
                <?php wp_nonce_field('list_manager_nonce_action', 'list_manager'); ?>
                <div class="focus-group">
                    <label class="gc-label required" id="cds-email" for="email">
                        <?php _e("Enter your email:", "cds-snc"); ?>
                        <span data-testid="asterisk" aria-hidden="true">*</span>
                        <i class="visually-hidden">Required Field</i>
                    </label>
                    <input class="gc-input-text" type="text" name="email" value=""/>
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
