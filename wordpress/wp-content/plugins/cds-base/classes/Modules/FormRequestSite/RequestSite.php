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

    public function checkboxField($name, $id, $value): void
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

    public function render($atts, $content = null): string
    {
        global $wp;
        ob_start();
        ?>

        <div class="gc-form-wrapper">
            <?php if (isset($_POST['site'])) {  // if "site" exists, use second half of form ?>
                <form id="request-form" method="POST" action="/wp-json/request/v1/process">
                
                <?php wp_nonce_field(
                    'request_form_nonce_action',
                    'request',
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

                <div class="buttons" style="margin-top: 1.5rem;">
                    <button class="gc-button gc-button" type="submit" id="submit">
                        <?php _e('Request site', 'cds-snc'); ?>
                    </button>
                </div>
                
            </form>


            <?php } else {  // if no "site", beginning of the form
                $current_url = home_url(add_query_arg([], $wp->request)); ?>       
            <form id="request-form-step-1" method="POST" action="<?php echo $current_url; ?>">
                
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
                        value=""
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
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Newsletter archive with emailing to a subscriber list.',
                        __('Newsletter archive with emailing to a subscriber list.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Website.',
                        __('Website.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
                        'usage[]',
                        'Internal website.',
                        __('Internal website.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
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
                        <?php _e('Who are the target audiences for your site?', 'cds-snc'); ?>
                    </label>
                    <p><?php _e('We use this information to improve GC Articles.', 'cds-snc');?></p>
                    
                    <div class="focus-group" style="margin-bottom: 1.75rem">
                    <?php $this->checkboxField(
                        'target[]',
                        'People who use your programs and services.',
                        __('People who use your programs and services.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'General public.',
                        __('General public.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'Subscribers.',
                        __('Subscribers.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
                        'target[]',
                        'Internal employees and/or community volunteers.',
                        __('Internal employees and/or community volunteers.', 'cds-snc'),
                    ); ?>
                    <?php $this->checkboxField(
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
                ></textarea>

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
