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

            $required_keys = ['goal', 'message'];
            $all_keys = array_merge($required_keys);
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
                        echo '<input type="hidden" name="' .  esc_html(sanitize_text_field($_key)) . '[]" value="' .  esc_html(sanitize_text_field($_v)) . '" />';
                    }
                } else {
                    echo '<input type="hidden" name="' .  esc_html(sanitize_text_field($_key)) . '" value="' .  esc_html(sanitize_text_field($_value)) . '" />';
                }
            }

                // Add current URL and path to request
                echo '<input type="hidden" name="url" value="' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" />';

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
                    ?>
                    </div>
                </div>
                <!-- end goal of your message -->

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
                ><?php echo esc_html(sanitize_text_field($all_values['message'])); ?></textarea>

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
