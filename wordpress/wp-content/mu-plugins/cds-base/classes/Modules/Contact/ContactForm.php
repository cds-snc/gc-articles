<?php

declare(strict_types=1);

namespace CDS\Modules\Contact;

class ContactForm
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        add_shortcode('contact-form', [$this, 'render']);
    }

    public function radioField($name, $id, $value): void
    {
        ?>
        <div class="gc-input-radio">
            <input
                    name="<?php echo $name; ?>"
                    class="gc-radio__input"
                    id="<?php echo $id; ?>"
                    type="radio"
                    required=""
                    value="<?php echo $id; ?>"
            />
            <label class="gc-radio-label" for="<?php echo $id; ?>"
            ><span class="radio-label-text"><?php echo $value; ?></span>
            </label
            >
        </div>
        <?php
    }

    public function render($atts, $content = null): string
    {
        global $wp;
        ob_start();
        ?>
        <div class="gc-form-wrapper">
            <?php
            if (isset($_POST['contact-type'])) {
                $contactType = $_POST['contact-type'];
                $heading = "";
                $label = "";
                switch ($contactType) {
                    case 'ask-a-question':
                        $heading = __("Ask a question", "cds-snc");
                        $label = __("Your question", "cds-snc");
                        break;
                    case 'get-technical-support':
                        $heading = __("Get technical support", "cds-snc");
                        $label = __("Describe the problem.", "cds-snc");
                        break;
                    case 'give-feedback':
                        $heading = __("Give feedback", "cds-snc");
                        $label = __("How can we do better?", "cds-snc");
                        break;
                    case 'set-up-a-demo-to-learn-more-about-GC-Notify':
                        $heading = __("Set up a demo to learn more about GC Notify", "cds-snc");
                        $label = __("Your contact information", "cds-snc");
                        break;
                    case 'other':
                        $heading = __("Tell us more", "cds-snc");
                        $label = __("Your message", "cds-snc");
                        break;
                }
                ?>
                <form id="contact-form" method="POST" action="/wp-json/contact/v1/process">
                    <h2><?php echo $heading; ?></h2>
                    <?php wp_nonce_field('contact_form_nonce_action', 'contact'); ?>
                    <input type="hidden" name="contact-type" value="<?php echo $contactType; ?>"/>
                    <div class="focus-group">
                        <label class="gc-label required" for="email" id="email-label">
                            <?php _e("Email", "cds-snc"); ?>
                        </label>
                        <input type="email" class="gc-input-text" id="email" required autocomplete="email"
                               placeholder="" name="email" value="">
                    </div>
                    <label data-testid="description" class="gc-label required" id="contact-label">
                        <?php echo $label; ?>
                    </label>
                    <textarea
                            data-testid="textarea"
                            class="gc-textarea"
                            id="message"
                            required
                            aria-describedby="contact-label"
                            placeholder=""
                            name="message"
                    ></textarea>
                    <div class="buttons">
                        <button class="gc-button gc-button" type="submit" id="submit">
                            <?php _e("Submit", "cds-snc"); ?>
                        </button>
                    </div>
                </form>
                <?php
            } else {
                $current_url = home_url(add_query_arg(array(), $wp->request));
                ?>
                <form id="contact-form-step-1" method="POST" action="<?php echo $current_url; ?>">
                    <div class="focus-group">
                        <?php $this->radioField("contact-type", "ask-a-question", __("Ask a question", "cds-snc")); ?>
                        <?php $this->radioField("contact-type", "get-technical-support",
                            __("Get technical support", "cds-snc")); ?>
                        <?php $this->radioField("contact-type", "give-feedback", __("Give feedback", "cds-snc")); ?>
                        <?php $this->radioField("contact-type", "set-up-a-demo-to-learn-more-about-GC-Notify",
                            __("Set up a demo to learn more about GC Notify", "cds-snc")); ?>
                        <?php $this->radioField("contact-type", "other", __("Other", "cds-snc")); ?>
                    </div>
                    <div class="buttons">
                        <button class="gc-button gc-button" type="submit" id="continue-submit">
                            <?php _e("Continue", "cds-snc"); ?>
                        </button>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
