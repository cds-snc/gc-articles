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
                
                <?php
                    wp_nonce_field(
                        'cds_form_nonce_action',
                        'cds-form-nonce',
                    );

                    Utils::textField(id: 'fullname', label: __('Full name', 'cds-snc'));
                    Utils::textField(id: 'email', label: __('Email', 'cds-snc'));
                ?>
            
                <!-- goal of your message -->
                <div role="group" aria-labelledby="goal_types">
                    <label class="gc-label" for="goal" id="goal_types">
                        <?php _e('Goal of your message', 'cds-snc'); ?>
                    </label>
                    <div id="usage-desc" class="gc-description" data-testid="description">
                        <?php _e('Your answer helps us make sure your message gets to the right people.', 'cds-snc');?>
                    </div>

                    <div class="focus-group">
                    <?php
                        Utils::radioField(
                            'goal',
                            'Ask a question.',
                            __('Ask a question.', 'cds-snc'),
                        );
                        Utils::radioField(
                            'goal',
                            'Get technical support.',
                            __('Get technical support.', 'cds-snc'),
                        );
                        Utils::radioField(
                            'goal',
                            'Give feedback.',
                            __('Give feedback.', 'cds-snc'),
                        );
                        Utils::radioField(
                            'goal',
                            'Schedule a demo to learn more about GC Articles.',
                            __('Schedule a demo to learn more about GC Articles.', 'cds-snc'),
                        );
                        Utils::radioField(
                            'goal',
                            'Other',
                            __('Other', 'cds-snc'),
                        );
                    ?>
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
                    <?php
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Blog.',
                            label: __('Blog.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Newsletter archive with emailing to a subscriber list.',
                            label: __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Website.',
                            label: __('Website.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Internal website.',
                            label: __('Internal website.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Something else.',
                            label: __('Something else.', 'cds-snc'),
                            ariaControls: 'optional-usage'
                        );
                    ?>
                    </div>
                    
                    <div id="optional-usage">
                        <?php Utils::textField(id: 'usage-optional', label: __('Other usage', 'cds-snc')); ?>
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
                    <?php
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'People who use your programs and services.',
                            value: __('People who use your programs and services.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'General public.',
                            value: __('General public.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Subscribers.',
                            value: __('Subscribers.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Internal employees and/or community volunteers.',
                            value: __('Internal employees and/or community volunteers.', 'cds-snc'),
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Other people.',
                            value: __('Other people.', 'cds-snc'),
                            ariaControls: 'optional-target'
                        );
                    ?>
                    </div>
                    <div id="optional-target">
                        <?php Utils::textField(id: 'target-optional', label: __('Other target audience', 'cds-snc')); ?>
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
                    <?php Utils::checkboxField(
                        'cc',
                        'Send a copy to your email.',
                        __('Send a copy to your email.', 'cds-snc'),
                    ); ?>
                </div>
                <!-- send me a copy -->

                <?php echo Utils::submitButton(__('Submit', 'cds-snc')); ?>
            </form>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
