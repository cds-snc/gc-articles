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
                    required
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
            <?php if (isset($_POST['contact-type'])) {

                $contactType = $_POST['contact-type'];
                $heading = '';
                $label = '';
                switch ($contactType) {
                    case 'request-site':
                        $heading = __('Request a site', 'cds-snc');
                        $label = __(
                            'Describe the site you would like',
                            'cds-snc',
                        );
                        break;
                    case 'ask-a-question':
                        $heading = __('Ask a question', 'cds-snc');
                        $label = __('Your question', 'cds-snc');
                        break;
                    case 'get-technical-support':
                        $heading = __('Get technical support', 'cds-snc');
                        $label = __('Describe the problem.', 'cds-snc');
                        break;
                    case 'give-feedback':
                        $heading = __('Give feedback', 'cds-snc');
                        $label = __('How can we do better?', 'cds-snc');
                        break;
                    case 'set-up-a-demo-to-learn-more-about-GC-Articles':
                        $heading = __(
                            'Set up a demo to learn more about GC Articles',
                            'cds-snc',
                        );
                        $label = __('Your contact information', 'cds-snc');
                        break;
                    case 'other':
                        $heading = __('Tell us more', 'cds-snc');
                        $label = __('Your message', 'cds-snc');
                        break;
                }
                ?>

                <?php if ($contactType === 'request-site'): ?>
                    
                    <form id="contact-form" method="POST" action="/wp-json/contact/v1/process">
                    <input type="hidden" name="contact-type" value="<?php echo $contactType; ?>"/> 
                    <h2><?php echo $heading; ?></h2>
                    <?php wp_nonce_field(
                        'contact_form_nonce_action',
                        'contact',
                    ); ?>


                    <div class="focus-group">
                        <label class="gc-label required" for="email" id="email-label">
                            <?php _e('Email', 'cds-snc'); ?>
                        </label>
                        <input type="email" class="gc-input-text" id="email" required autocomplete="email"
                               name="email" value="">
                    </div>

                    <!-- collection name  -->
                    <div class="focus-group">
                    <label class="gc-label required" id="collection-name-label" for="gc-collection-name">
                        <?php _e("What do you want to name your GC Articles collection?", "cds-snc"); ?>
                    </label>
                    <input
                            type="text"
                            class="gc-input-text"
                            id="gc-collection-name"
                            required
                            aria-describedby="collection-name-label"
                            placeholder=""
                            name="gc-collection-name"
                    ></input>
                    </div>

                    <!-- heard about  -->
                    <div class="focus-group">
                    <label class="gc-label required" id="heard-about-from=label" for="heard-about-from">
                        <?php _e("How did you hear about GC Articles?", "cds-snc"); ?>
                    </label>
                    <input
                            type="text"
                            class="gc-input-text"
                            id="heard-about-from"
                            required
                            aria-describedby="heard-about-from-label"
                            placeholder=""
                            name="heard-about-from"
                    ></input>
                    </div>

                     <!-- purpose  -->
                     <div class="focus-group">
                     <label class="gc-label required" id="purpose-label" for="purpose">
                        <?php _e("What will you use your GC Articles collection for?
", "cds-snc"); ?>
                    </label>
                    <textarea
                            data-testid="textarea"
                            class="gc-textarea"
                            id="purpose"
                            required
                            aria-describedby="purpose-label"
                            placeholder=""
                            name="purpose"
                    ></textarea>
                    </div>


                     <!-- Notify - sending_integration -->
                     <div class="focus-group">
                     <label class="gc-label required" id="sending-integration-label" for="sending-integration">
                        <?php _e("Do you require a GC Notify email sending integration? if yes, what for?
", "cds-snc"); ?>
                    </label>
                    <textarea
                            data-testid="textarea"
                            class="gc-textarea"
                            id="sending-integration"
                            required
                            aria-describedby="sending-integration-label"
                            placeholder=""
                            name="sending-integration"
                    ></textarea>
                    </div>

                    <div class="buttons">
                        <button class="gc-button gc-button" type="submit" id="submit">
                            <?php _e('Submit', 'cds-snc'); ?>
                        </button>
                    </div>
                </form>
                
                <?php else: ?>
                <form id="contact-form" method="POST" action="/wp-json/contact/v1/process">
                    <h2><?php echo $heading; ?></h2>
                    <?php wp_nonce_field(
                        'contact_form_nonce_action',
                        'contact',
                    ); ?>
                    <input type="hidden" name="contact-type" value="<?php echo $contactType; ?>"/>
                    <div class="focus-group">
                        <label class="gc-label required" for="email" id="email-label">
                            <?php _e('Email', 'cds-snc'); ?>
                        </label>
                        <input type="email" class="gc-input-text" id="email" required autocomplete="email"
                               name="email" value="">
                    </div>
                    <label data-testid="description" class="gc-label required" id="contact-label" for="message">
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
                            <?php _e('Submit', 'cds-snc'); ?>
                        </button>
                    </div>
                </form>

                <?php endif; ?>
               <?php
            } else {
                $current_url = home_url(add_query_arg([], $wp->request)); ?>
                <form id="contact-form-step-1" method="POST" action="<?php echo $current_url; ?>">
                    <div role="group" aria-labelledby="contact_types">
                        <label for="contact_types">
                            <span id="contact_types" class="hidden"><?php _e(
                                'Contact Types',
                                'cds-snc',
                            ); ?></span>
                        </label>
                        <div class="focus-group" style="margin-bottom: 1.75rem">
                            <?php $this->radioField(
                                'contact-type',
                                'request-site',
                                __('Request a site', 'cds-snc'),
                            ); ?>
                            <?php $this->radioField(
                                'contact-type',
                                'ask-a-question',
                                __('Ask a question', 'cds-snc'),
                            ); ?>
                            <?php $this->radioField(
                                'contact-type',
                                'get-technical-support',
                                __('Get technical support', 'cds-snc'),
                            ); ?>
                            <?php $this->radioField(
                                'contact-type',
                                'give-feedback',
                                __('Give feedback', 'cds-snc'),
                            ); ?>
                            <?php $this->radioField(
                                'contact-type',
                                'set-up-a-demo-to-learn-more-about-GC-Articles',
                                __(
                                    'Set up a demo to learn more about GC Articles',
                                    'cds-snc',
                                ),
                            ); ?>
                            <?php $this->radioField(
                                'contact-type',
                                'other',
                                __('Other', 'cds-snc'),
                            ); ?>
                        </div>
                    </div>
                    <div class="buttons">
                        <button class="gc-button gc-button" type="submit" id="continue-submit">
                            <?php _e('Continue', 'cds-snc'); ?>
                        </button>
                    </div>
                </form>
                <?php
            } ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
