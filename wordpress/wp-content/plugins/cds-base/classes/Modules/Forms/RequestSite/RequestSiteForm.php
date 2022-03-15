<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\RequestSite;

use CDS\Modules\Forms\Utils;

class RequestSiteForm
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        add_shortcode('request-site-form', [$this, 'render']);
    }

    public function errorMessage(array $error_ids): string
    {
        $errorEl = '<div id="request-error" class="gc-alert gc-alert--error gc-alert--validation" data-testid="alert" tabindex="0" role="alert">';
        $errorEl .= '<div class="gc-alert__body">';
        $errorEl .= '<h2 class="gc-h3">' . __('Please complete the required field(s) to continue', 'cds-snc') . '</h2>';
        $errorEl .= '<ol class="gc-ordered-list">';
        foreach ($error_ids as $id) {
            $errorEl .= '<li><a href="#' . $id . '" class="gc-error-link">' . $id . '</a></li>';
        }
        $errorEl .= '</ol></div></div>';
        return $errorEl;
    }

    public function render($atts, $content = null): string
    {
        global $wp;
        ob_start();
        ?>

        <div class="gc-form-wrapper">
            <?php

            $required_keys = ['site', 'usage', 'target', 'timeline'];
            $all_keys = array_merge($required_keys, ['usage-other', 'target-other']);
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

                <form id="cds-form" method="POST" action="/wp-json/request/v1/process">
                <p>
                    <?php
                    echo _e('Site administrator details. ', 'cds-snc');
                    echo _e('(Step 2 of 2)', 'cds-snc');
                    ?>
                </p>

                <?php
                    wp_nonce_field(
                        'cds_form_nonce_action',
                        'cds-form-nonce',
                    );

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

                    Utils::textField(id: 'fullname', label: __('Full name', 'cds-snc'));
                    Utils::textField(
                        id: 'email',
                        label: __('Email', 'cds-snc'),
                        description: __('Must be a Government of Canada email address. <br />Currently, we accept email addresses ending in gc.ca, canada.ca, or cds-snc.ca.'),
                    );
                    Utils::textField(id: 'role', label: __('Job title or role', 'cds-snc'));
                    Utils::textField(
                        id: 'department',
                        label: __('Department or agency', 'cds-snc'),
                        description: __('GC Articles is only for government employees.', 'cds-snc')
                    );
                ?>

                <div class="focus-group">
                    <ul>
                        <li>
                            <?php _e('Reminder: Only use GC Articles for information that is not sensitive and can be shared publicly.', 'cds-snc'); ?>
                        </li>
                        <li>
                            <?php _e('Make sure you’re allowed to publsh this information on behalf of the Government of Canada.', 'cds-snc'); ?>
                        </li>
                    </ul>
                </div>

                <!-- send me a copy -->
                <div>
                    <?php echo Utils::checkboxField(
                        'cc',
                        'Send a copy to your email.',
                        __('Send a copy to your email.', 'cds-snc'),
                    ); ?>
                </div>
                <!-- send me a copy -->

                <?php echo Utils::submitButton(__('Request site', 'cds-snc')); ?>
            </form>


            <?php } else {  // if no "site", beginning of the form
                $current_url = home_url(add_query_arg([], $wp->request)); ?>

                <?php
                if ($is_error) {
                    echo $this->errorMessage($empty_values);
                }
                ?>
            <form id="cds-form-step-1" method="POST" action="<?php echo $current_url; ?>">
                <p>
                    <?php
                    echo _e('Tell us about your site. ', 'cds-snc');
                    echo _e('(Step 1 of 2)', 'cds-snc');
                    ?>
                </p>

                <?php wp_nonce_field(
                    'cds_form_nonce_action',
                    'cds-form-nonce',
                ); ?>
            
                <!-- start site -->
                <?php Utils::textField(
                    id: 'site',
                    label: __('English title of your site', 'cds-snc'),
                    description: __('This title will appear at the top of your site. You can change this later.', 'cds-snc'),
                    value: $all_values['site']
                ); ?>
                <!-- end site -->

                <!-- usage -->
                <div role="group" aria-labelledby="usage_types">
                    <label class="gc-label" for="usage" id="usage_types">
                        <?php _e('What will you use your site for?', 'cds-snc'); ?>
                    </label>
                    <div id="usage-desc" class="gc-description" data-testid="description">
                        <?php _e('We use this information to improve GC Articles.', 'cds-snc');?>
                    </div>
                    
                    <div class="focus-group">
                        <?php echo Utils::checkboxField(
                            'usage[]',
                            'Blog.',
                            __('Blog.', 'cds-snc'),
                            $all_values['usage']
                        ); ?>
                        <?php echo Utils::checkboxField(
                            'usage[]',
                            'Newsletter archive with emailing to a subscriber list.',
                            __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                            $all_values['usage']
                        ); ?>
                        <?php echo Utils::checkboxField(
                            'usage[]',
                            'Website.',
                            __('Website.', 'cds-snc'),
                            $all_values['usage']
                        ); ?>
                        <?php echo Utils::checkboxField(
                            'usage[]',
                            'Internal website.',
                            __('Internal website.', 'cds-snc'),
                            $all_values['usage'],
                        ); ?>

                        <?php echo Utils::checkboxField(
                            'usage[]',
                            'Something else.',
                            __('Something else.', 'cds-snc'),
                            $all_values['usage'],
                            'optional-usage'
                        ); ?>
                    </div>
                    <div id="optional-usage" aria-hidden="false">
                        <?php echo Utils::textField(id: 'usage-optional', label: __('Other usage', 'cds-snc')); ?>
                    </div>
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences for your site?', 'cds-snc'); ?>
                    </label>
                    <div id="target-desc" class="gc-description" data-testid="description">
                        <?php _e('We use this information to improve GC Articles.', 'cds-snc');?>
                    </div>
                    
                    <div class="focus-group">
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'People who use your programs and services.',
                        __('People who use your programs and services.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'General public.',
                        __('General public.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Subscribers.',
                        __('Subscribers.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Internal employees and/or community volunteers.',
                        __('Internal employees and/or community volunteers.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php echo Utils::checkboxField(
                        'target[]',
                        'Other people.',
                        __('Other people.', 'cds-snc'),
                        $all_values['target'],
                        'optional-target'
                    ); ?>
                    </div>
                    <div id="optional-target" aria-hidden="false">
                        <?php echo Utils::textField(id: 'target-optional', label: __('Other target audience', 'cds-snc')); ?>
                    </div>
                </div>

                <!-- timeline -->
                <div class="focus-group">
                    <label data-testid="description" class="gc-label" id="timeline-label" for="timeline">
                        <?php _e('When do you plan to make your site public?', 'cds-snc'); ?>
                    </label>
                    <div id="timeline-desc" class="gc-description" data-testid="description">
                        <?php _e('We look for sites that will launch in the next 2 months. Sites that linger for longer than 2 months without being made public reduce our ranking in search engines.', 'cds-snc'); ?>
                    </div>
                    <textarea
                        data-testid="textarea"
                        class="gc-textarea"
                        id="timeline"
                        required
                        placeholder=""
                        name="timeline"
                    ><?php echo $all_values['timeline']; ?></textarea>
                </div>

                <?php echo Utils::submitButton(__('Next', 'cds-snc')); ?>
            </form>
            <?php }  // end of the big if ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
