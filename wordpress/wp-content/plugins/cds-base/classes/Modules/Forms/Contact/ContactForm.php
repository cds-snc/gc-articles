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
        <div class="gc-form-wrapper">
        <?php

            $required_keys = ['goal', 'usage', 'target', 'message'];
            $all_keys = array_merge($required_keys, ['optional-usage-value', 'optional-target-value']);
            $all_values = [];
            $empty_values = [];

            // create array of keys and values
        foreach ($all_keys as $_key) {
            $all_values[$_key] = is_array($_POST[$_key] ?? '') ? $_POST[$_key] : stripslashes($_POST[$_key] ?? '');
        }

            // find all empty values
        foreach ($all_values as $_key => $_value) {
            // if it's a required key AND it's empty
            if (in_array($_key, $required_keys) && $_value === '') {
                array_push($empty_values, $_key);
            }
        }

            // if all required fields are empty, it's a new form. If some are but not others, it's an error
            $is_error = count($empty_values) !== count($required_keys);

        if (
                count($empty_values) === 0
        ) {  // no empty 'required' keys exist, use second part of form
            ?>

            <form id="cds-form" method="POST" action="/wp-json/contact/v1/process">

            <?php

                // add hidden fields for previous answers
            foreach ($all_values as $_key => $_value) {
                // if is array, iterate through each array value
                if (is_array($_value)) {
                    foreach ($_value as $_v) {
                        echo '<input type="hidden" name="' . sanitize_text_field($_key) . '[]" value="' . sanitize_text_field($_v) . '" />';
                    }
                } else {
                    echo '<input type="hidden" name="' . sanitize_text_field($_key) . '" value="' . sanitize_text_field($_value) . '" />';
                }
            }

                wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                );

                echo '<p>';
                echo _e('(Step 2 of 2)', 'cds-snc');
                echo '</p>';

                Utils::textField(id: 'fullname', label: __('Full name', 'cds-snc'));
                Utils::textField(id: 'email', label: __('Email', 'cds-snc'));
                Utils::textField(id: 'department', label: __('Department', 'cds-snc'));

                // start: send me a copy
                echo '<div>';
                Utils::checkboxField(
                    'cc',
                    'Send a copy to your email.',
                    __('Send a copy to your email.', 'cds-snc'),
                );
                echo '</div>';
                // end: send me a copy

                Utils::submitButton(__('Submit', 'cds-snc'));
            ?>
            </form>

        <?php } else {  // if no "site", beginning of the form
                $current_url = home_url(add_query_arg([], $wp->request)); ?>

                <?php
                if ($is_error) {
                    Utils::errorMessage($empty_values);
                }
                ?>

            <form id="cds-form-step-1" method="POST" action="<?php echo $current_url; ?>">
            
                <p>
                    <?php echo _e('(Step 1 of 2)', 'cds-snc'); ?>
                </p>

                <?php wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                ); ?>

                <!-- goal of your message -->
                <div role="group" aria-labelledby="goal_types" id="goal">
                    <label class="gc-label" for="goal" id="goal_types">
                        <?php _e('Goal of your message', 'cds-snc'); ?>
                    </label>
                    <div id="goal-desc" class="gc-description" data-testid="description">
                        <?php _e('Your answer helps us make sure your message gets to the right people.', 'cds-snc');?>
                    </div>

                    <div class="focus-group">
                    <?php
                        Utils::radioField(
                            'goal',
                            'Ask a question.',
                            __('Ask a question.', 'cds-snc'),
                            val: $all_values['goal']
                        );
                        Utils::radioField(
                            'goal',
                            'Get technical support.',
                            __('Get technical support.', 'cds-snc'),
                            val: $all_values['goal']
                        );
                        Utils::radioField(
                            'goal',
                            'Give feedback.',
                            __('Give feedback.', 'cds-snc'),
                            val: $all_values['goal']
                        );
                        Utils::radioField(
                            'goal',
                            'Schedule a demo to learn more about GC Articles.',
                            __('Schedule a demo to learn more about GC Articles.', 'cds-snc'),
                            val: $all_values['goal']
                        );
                        Utils::radioField(
                            'goal',
                            'Other',
                            __('Other', 'cds-snc'),
                            val: $all_values['goal']
                        );
                    ?>
                    </div>
                </div>
                <!-- end goal of your message -->

                <!-- usage -->
                <div role="group" aria-labelledby="usage_types" id="usage">
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
                            value: __('Blog.', 'cds-snc'),
                            vals: $all_values['usage']
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Newsletter archive with emailing to a subscriber list.',
                            value: __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                            vals: $all_values['usage']
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Website.',
                            value: __('Website.', 'cds-snc'),
                            vals: $all_values['usage']
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Internal website.',
                            value: __('Internal website.', 'cds-snc'),
                            vals: $all_values['usage']
                        );
                        Utils::checkboxField(
                            name: 'usage[]',
                            id: 'Something else.',
                            value: __('Something else.', 'cds-snc'),
                            vals: $all_values['usage'],
                            ariaControls: 'optional-usage'
                        );
                    ?>
                    </div>
                    
                    <div id="optional-usage">
                        <?php Utils::textField(id: 'optional-usage-value', label: __('Other usage', 'cds-snc'), value: $all_values['optional-usage-value']); ?>
                    </div>
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types" id="target">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences you’re thinking about? (optional)', 'cds-snc'); ?>
                    </label>
                    <div id="target-desc" class="gc-description" data-testid="description">
                        <?php _e('We use this information to improve GC Articles.', 'cds-snc');?>
                    </div>
                    
                    <div class="focus-group">
                    <?php
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'People who use your programs and services.',
                            value: __('People who use your programs and services.', 'cds-snc'),
                            vals: $all_values['target']
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'General public.',
                            value: __('General public.', 'cds-snc'),
                            vals: $all_values['target']
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Subscribers.',
                            value: __('Subscribers.', 'cds-snc'),
                            vals: $all_values['target']
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Internal employees and/or community volunteers.',
                            value: __('Internal employees and/or community volunteers.', 'cds-snc'),
                            vals: $all_values['target']
                        );
                        Utils::checkboxField(
                            name: 'target[]',
                            id: 'Other people.',
                            value: __('Other people.', 'cds-snc'),
                            vals: $all_values['target'],
                            ariaControls: 'optional-target'
                        );
                    ?>
                    </div>
                    <div id="optional-target">
                        <?php Utils::textField(id: 'optional-target-value', label: __('Other target audience', 'cds-snc'), value: $all_values['optional-target-value']); ?>
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
                ><?php echo $all_values['message']; ?></textarea>

                <?php Utils::submitButton(__('Next', 'cds-snc')); ?>
            </form>

        <?php }  // end of the big if ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
