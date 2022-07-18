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

    public function render($atts, $content = null): string
    {
        global $wp;
        ob_start();
        ?>

        <div class="gc-form-wrapper">
            <?php

            $required_keys = ['site', 'timeline'];
            $all_keys = array_merge($required_keys, ['optional-usage-value', 'optional-target-value', 'usage', 'target']);
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
                    echo _e('Site administrator details.', 'cds-snc');
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

                    // Add current URL and path to request
                    echo '<input type="hidden" name="url" value="' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" />';

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
                            <?php _e('Make sure you’re allowed to publish this information on behalf of the Government of Canada.', 'cds-snc'); ?>
                        </li>
                    </ul>
                </div>

                <!-- send me a copy -->
                <div>
                    <?php Utils::checkboxField(
                        'cc',
                        'Send a copy to your email.',
                        __('Send a copy to your email.', 'cds-snc'),
                    ); ?>
                </div>
                <!-- send me a copy -->

                <?php Utils::submitButton(__('Request site', 'cds-snc')); ?>
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
                <div class="focus-group">
                    <label class="gc-label" for="site" id="site-label">
                        <?php _e('English title of your site', 'cds-snc'); ?>
                    </label>
                    <div id="site-desc" class="gc-description" data-testid="description">
                        <?php _e('This title will appear at the top of your site. You can change this later.', 'cds-snc'); ?>
                    </div>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="site" 
                        name="site" 
                        value="<?php echo $all_values['site']; ?>"
                        aria-describedby="url-typer"
                        required
                    />
                    <div id="url-typer" class="url-typer gc-description" aria-live="polite" aria-atomic="true">
                        <div class="url-typer--empty"><?php _e('Enter a title to preview your URL', 'cds-snc'); ?></div>
                        <div class="url-typer--message displayNone"><?php _e('Your URL preview:', 'cds-snc'); ?> <strong>articles.alpha.canada.ca/<span id="url-typer__preview"></span></strong></div>
                    </div>
                </div>
                <!-- end site -->

                <!-- usage -->
                <div role="group" aria-labelledby="usage_types" id="usage">
                    <label class="gc-label" for="usage" id="usage_types">
                        <?php _e('What will you use your site for?', 'cds-snc'); ?>
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
                    <div id="optional-usage" aria-hidden="false">
                        <?php Utils::textField(id: 'optional-usage-value', label: __('Other usage', 'cds-snc'), value: $all_values['optional-usage-value']); ?>
                    </div>
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types" id="target">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences for your site?', 'cds-snc'); ?>
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
                    <div id="optional-target" aria-hidden="false">
                        <?php Utils::textField(id: 'optional-target-value', label: __('Other target audience', 'cds-snc'), value: $all_values['optional-target-value']); ?>
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
                    ><?php echo esc_html(sanitize_text_field($all_values['timeline'])); ?></textarea>
                </div>

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
