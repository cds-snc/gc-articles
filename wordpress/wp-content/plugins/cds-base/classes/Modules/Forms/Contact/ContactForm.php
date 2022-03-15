<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Contact;

use CDS\Modules\Forms\Utils;

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

    public function render($atts, $content = null): string
    {
        global $wp;
        ob_start();
        ?>
        <!--
            fullname
        -->
        <div class="gc-form-wrapper">
            <form id="cds-form" method="POST" action="/wp-json/contact/v1/process">
                
                <?php wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                ); ?>
            
                <?php echo Utils::textField('fullname', __('Full name', 'cds-snc')); ?>
                
                <?php echo Utils::textField('email', __('Email', 'cds-snc')); ?>
            
                <!-- goal of your message -->
                <div role="group" aria-labelledby="goal_types">
                    <label class="gc-label" for="goal" id="goal_types">
                        <?php _e('Goal of your message', 'cds-snc'); ?>
                    </label>
                    <div id="usage-desc" class="gc-description" data-testid="description">
                        <?php _e('Your answer helps us make sure your message gets to the right people.', 'cds-snc');?>
                    </div>

                    <div class="focus-group">
                    <?php echo Utils::radioField(
                        'goal',
                        'Ask a question.',
                        __('Ask a question.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::radioField(
                        'goal',
                        'Get technical support.',
                        __('Get technical support.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::radioField(
                        'goal',
                        'Give feedback.',
                        __('Give feedback.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::radioField(
                        'goal',
                        'Schedule a demo to learn more about GC Articles.',
                        __('Schedule a demo to learn more about GC Articles.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::radioField(
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
                    <div id="usage-desc" class="gc-description" data-testid="description">
                        <?php _e('We use this information to improve GC Articles.', 'cds-snc');?>
                    </div>

                    <div class="focus-group">
                    <?php echo Utils::checkboxField(
                        'usage[]',
                        'Blog.',
                        __('Blog.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'usage[]',
                        'Newsletter archive with emailing to a subscriber list.',
                        __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'usage[]',
                        'Website.',
                        __('Website.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'usage[]',
                        'Internal website.',
                        __('Internal website.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'usage[]',
                        'Something else.',
                        __('Something else.', 'cds-snc'),
                        null, // we don't have previous values to pass in
                        'optional-usage'
                    ); ?>
                    </div>
                    
                    <div id="optional-usage">
                        <?php echo Utils::textField('usage-optional', __('Other usage', 'cds-snc')); ?>
                    </div>
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences you’re thinking about? (optional)', 'cds-snc'); ?>
                    </label>
                    <div id="usage-desc" class="gc-description" data-testid="description">
                        <?php _e('We use this information to improve GC Articles.', 'cds-snc');?>
                    </div>
                    
                    <div class="focus-group">
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'People who use your programs and services.',
                        __('People who use your programs and services.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'General public.',
                        __('General public.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Subscribers.',
                        __('Subscribers.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Internal employees and/or community volunteers.',
                        __('Internal employees and/or community volunteers.', 'cds-snc'),
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Other people.',
                        __('Other people.', 'cds-snc'),
                        null, // we don't have previous values to pass in
                        'optional-target'
                    ); ?>
                    </div>
                    <div id="optional-target">
                        <?php echo Utils::textField('target-optional', __('Other target audience', 'cds-snc')); ?>
                    </div>
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
                    <?php echo Utils::checkboxField(
                        'cc',
                        'Send a copy to your email.',
                        __('Send a copy to your email.', 'cds-snc'),
                    ); ?>
                </div>
                <!-- send me a copy -->

                <div class="buttons">
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
