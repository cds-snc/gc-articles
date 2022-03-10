<?php

declare(strict_types=1);

namespace CDS\Modules\FormRequestSite;

class RequestSite
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        add_shortcode('request-site-form', [$this, 'render']);
    }

    public function checkboxField($name, $id, $value, $vals = []): void
    {
        // set to empty array if a non-array is passed in
        $vals = is_array($vals) ? $vals : [];
        $checked = in_array($value, $vals);
        ?>
         <div class="gc-input-checkbox">
            <input
                name="<?php echo $name; ?>"
                class="gc-input-checkbox__input"
                id="<?php echo sanitize_title($id); ?>"
                type="checkbox"
                value="<?php echo $id; ?>"
                <?php if ($checked) {
                    echo 'checked';
                } ?>
            />
            <label class="gc-checkbox-label" for="<?php echo sanitize_title($id); ?>">
            <span class="checkbox-label-text"><?php echo $value; ?></span>
            </label
            >
        </div>
        <?php
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
                $all_values[$_key] = $_POST[$_key] ?? '';
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

                <form id="request-form" method="POST" action="/wp-json/request/v1/process">
                <p>
                    <?php
                    echo _e('Site administrator details. ', 'cds-snc');
                    echo _e('(Step 2 of 2)', 'cds-snc');
                    ?>
                </p>

                <?php wp_nonce_field(
                    'request_form_nonce_action',
                    'request',
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

                ?>

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
                    <div id="email-desc" class="gc-description" data-testid="description">
                        <?php _e('Must be a Government of Canada email address. Currently, we accept email addresses ending in:'); ?>
                        <ul>
                            <li>gc.ca</li>
                            <li>canada.ca</li>
                            <li>cds-snc.ca</li>
                        </ul>
                    </div>
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

                <!-- start role -->
                <div class="focus-group">
                    <label class="gc-label" for="role" id="fullname-role">
                        <?php _e('Job title or role', 'cds-snc'); ?>
                    </label>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="role" 
                        required 
                        name="role" 
                        value=""
                    />
                </div>
                <!-- end role -->

                <!-- start department -->
                <div class="focus-group">
                    <label class="gc-label" for="department" id="department-label">
                        <?php _e('Department or agency', 'cds-snc'); ?>
                    </label>
                    <div id="department-desc" class="gc-description" data-testid="description">
                        <?php _e('GC Articles is only for government employees.', 'cds-snc'); ?>
                    </div>
                    <input 
                        type="text" 
                        class="gc-input-text" 
                        id="department" 
                        required 
                        name="department" 
                        value=""
                    />
                </div>
                <!-- end department -->

                <ul>
                    <li>
                        <?php _e('Reminder: Only use GC Articles for information that is not sensitive and can be shared publicly.', 'cds-snc'); ?>
                    </li>
                    <li>
                        <?php _e('Make sure you’re allowed to publsh this information on behalf of the Government of Canada.', 'cds-snc'); ?>
                    </li>
                </ul>

                <!-- send me a copy -->
                <div>
                    <div class="gc-input-checkbox">
                        <input
                            name="cc"
                            class="gc-input-checkbox__input"
                            id="send-a-copy-to-your-email"
                            type="checkbox"
                            value="<?php _e('Send a copy to your email.', 'cds-snc'); ?>"
                        />
                        <label class="gc-checkbox-label" for="send-a-copy-to-your-email">
                            <span class="checkbox-label-text"><?php _e('Send a copy to your email.', 'cds-snc'); ?></span>
                        </label>
                    </div>
                </div>
                <!-- send me a copy -->

                <div class="buttons" style="margin-top: 1.5rem;">
                    <button class="gc-button gc-button" type="submit" id="submit">
                        <?php _e('Request site', 'cds-snc'); ?>
                    </button>
                </div>
                
            </form>


            <?php } else {  // if no "site", beginning of the form
                $current_url = home_url(add_query_arg([], $wp->request)); ?>

                <?php
                if ($is_error) {
                    echo $this->errorMessage($empty_values);
                }
                ?>
            <form id="request-form-step-1" method="POST" action="<?php echo $current_url; ?>">
                <p>
                    <?php
                    echo _e('Tell us about your site. ', 'cds-snc');
                    echo _e('(Step 1 of 2)', 'cds-snc');
                    ?>
                </p>

                <?php wp_nonce_field(
                    'request_form_nonce_action',
                    'request',
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
                        required 
                        name="site" 
                        value="<?php echo $all_values['site']; ?>"
                    />
                </div>
                <!-- end name -->

                <!-- usage -->
                <div role="group" aria-labelledby="usage_types">
                    <label class="gc-label" for="usage" id="usage_types">
                        <?php _e('What will you use your site for?', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('We use this information to improve GC Articles.', 'cds-snc');?></p>
                    
                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->checkboxField(
                        'usage[]',
                        'Blog.',
                        __('Blog.', 'cds-snc'),
                        $all_values['usage']
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Newsletter archive with emailing to a subscriber list.',
                        __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                        $all_values['usage']
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Website.',
                        __('Website.', 'cds-snc'),
                        $all_values['usage']
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Internal website.',
                        __('Internal website.', 'cds-snc'),
                        $all_values['usage']
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Something else.',
                        __('Something else.', 'cds-snc'),
                        $all_values['usage']
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
                        value="<?php echo $all_values['usage-other']; ?>"
                    />
                </div>
                <!-- end usage -->

                <!-- target -->
                <div role="group" aria-labelledby="target_types">
                    <label class="gc-label" for="target" id="target_types">
                        <?php _e('Who are the target audiences for your site?', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('We use this information to improve GC Articles.', 'cds-snc');?></p>
                    
                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->checkboxField(
                        'target[]',
                        'People who use your programs and services.',
                        __('People who use your programs and services.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'General public.',
                        __('General public.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'Subscribers.',
                        __('Subscribers.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'Internal employees and/or community volunteers.',
                        __('Internal employees and/or community volunteers.', 'cds-snc'),
                        $all_values['target']
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'Other people.',
                        __('Other people.', 'cds-snc'),
                        $all_values['target']
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
                        value="<?php echo $all_values['target-other']; ?>"
                    />
                </div>

                <!-- timeline -->
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

                <div class="buttons" style="margin-top: 1.5rem;">
                    <button class="gc-button gc-button" type="submit" id="submit">
                        <?php _e('Next', 'cds-snc'); ?>
                    </button>
                </div>
                
            </form>
            <?php }  // end of the big if ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
}
