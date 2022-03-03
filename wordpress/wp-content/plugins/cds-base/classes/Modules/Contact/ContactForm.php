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

    public function checkBox($name, $id, $value): void
    {
        ?>
        <div class="gc-input-checkbox">
            <input
                name="<?php echo $name; ?>"
                class="gc-input-checkbox__input"
                id="<?php echo sanitize_title($id); ?>"
                type="checkbox"
                value="<?php echo $id; ?>"
            />
            <label class="gc-checkbox-label" for="<?php echo sanitize_title($id); ?>">
            <span class="checkbox-label-text"><?php echo $value; ?></span>
            </label
            >
        </div>
        <?php
    }

    public function radioField($name, $id, $value): void
    {
        ?>
        <div class="gc-input-radio">
            <input
                name="<?php echo $name; ?>"
                class="gc-radio__input"
                id="<?php echo sanitize_title($id); ?>"
                type="radio"
                required
                value="<?php echo $id; ?>"
            />
            <label class="gc-radio-label" for="<?php echo sanitize_title($id); ?>">
            <span class="radio-label-text"><?php echo $value; ?></span>
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
        <!--
            fullname
        -->
        <div class="gc-form-wrapper">
            <form id="contact-form" method="POST" action="/wp-json/contact/v1/process">
                
                <?php wp_nonce_field(
                    'contact_form_nonce_action',
                    'contact',
                ); ?>
            
                <!-- start name -->
                <div class="focus-group">
                    <label class="gc-label" for="fullname" id="fullname-label">
                        <?php _e('Full name', 'cds-snc'); ?>
                    </label>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="fullname" 
                        required 
                        name="fullname" 
                        value=""
                    />
                </div>
                <!-- end name -->
                
                <!-- start email -->
                <div class="focus-group">
                    <label class="gc-label" for="email" id="email-label">
                        <?php _e('Email', 'cds-snc'); ?>
                    </label>
                    <input 
                        type="email" 
                        class="gc-input-text" 
                        id="email" 
                        required 
                        autocomplete="email"
                        name="email" 
                        value=""
                    />
                </div>
                <!-- end email -->
            
                <!-- goal of your message -->
                <div role="group" aria-labelledby="goal_types">
                    <label class="gc-label" for="goal" id="goal_types">
                        <?php _e('Goal of your message', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('Your answer helps us make sure your message gets to the right people.', 'cds-snc');?></p>

                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->radioField(
                        'goal',
                        'Ask a question.',
                        __('Ask a question.', 'cds-snc'),
                    ); ?>
                    <?php $this->radioField(
                        'goal',
                        'Get technical support.',
                        __('Get technical support.', 'cds-snc'),
                    ); ?>
                    <?php $this->radioField(
                        'goal',
                        'Give feedback.',
                        __('Give feedback.', 'cds-snc'),
                    ); ?>
                    <?php $this->radioField(
                        'goal',
                        'Schedule a demo to learn more about GC Articles.',
                        __('Schedule a demo to learn more about GC Articles.', 'cds-snc'),
                    ); ?>
                    <?php $this->radioField(
                        'goal',
                        'Other',
                        __('Other', 'cds-snc'),
                    ); ?>
                </div>
                <!-- end goal of your message -->

                <!-- usage -->
                <div role="group" aria-labelledby="usage_types">
                    <label class="gc-label" for="usage" id="usage_types">
                        <?php _e('What are you thinking about using GC Articles for? (optional)', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('We use this information to improve GC Articles.', 'cds-snc');?></p>
                    
                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->checkBox(
                        'usage[]',
                        'Blog.',
                        __('Blog.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'usage[]',
                        'Newsletter archive with emailing to a subscriber list.',
                        __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'usage[]',
                        'Website.',
                        __('Website.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'usage[]',
                        'Internal website.',
                        __('Internal website.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'usage[]',
                        'Something else.',
                        __('Something else.', 'cds-snc'),
                    ); ?>
                    </div>
                    
                    <label class="gc-label" for="usage-other" id="usage-other-label" class="hidden"">
                        <?php _e('Other usage', 'cds-snc'); ?>
                    </label>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="usage-other" 
                        name="usage-other" 
                        value=""
                    />
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences you’re thinking about? (optional)', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('We use this information to improve GC Articles.', 'cds-snc');?></p>
                    
                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->checkBox(
                        'target[]',
                        'People who use your programs and services.',
                        __('People who use your programs and services.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'target[]',
                        'General public.',
                        __('General public.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'target[]',
                        'Subscribers.',
                        __('Subscribers.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'target[]',
                        'Internal employees and/or community volunteers.',
                        __('Internal employees and/or community volunteers.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkBox(
                        'target[]',
                        'Other people.',
                        __('Other people.', 'cds-snc'),
                    ); ?>
                    </div>
                    <label class="gc-label" for="target-other" id="target-other-label" class="hidden"">
                        <?php _e('Other target audience', 'cds-snc'); ?>
                    </label>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="target-other" 
                        name="target-other" 
                        value=""
                    />
                </div>
                <!-- target -->
                <label data-testid="description" class="gc-label" id="message-label" for="message">
                    <?php _e('Your message', 'cds-snc'); ?>
                </label>
                <textarea
                    data-testid="textarea"
                    class="gc-textarea"
                    id="message"
                    required
                    placeholder=""
                    name="message"
                ></textarea>


                <!-- send me a copy -->
                <div>
                    <?php $this->checkBox(
                        'cc',
                        'Send a copy to your email.',
                        __('Send a copy to your email.', 'cds-snc'),
                    ); ?>
                </div>
                <!-- send me a copy -->

                <div class="buttons" style="margin-top: 1.5rem;">
                    <button class="gc-button gc-button" type="submit" id="submit">
                        <?php _e('Submit', 'cds-snc'); ?>
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
