<?php

use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Utils;

function cds_default_get_encrypted_key()
{
    if ((Utils::isWpEnv()) || (defined('WP_CLI') && WP_CLI)) {
        return getenv('ENCRYPTION_KEY') ?: "base64:cELNoBToBqa9NtubmEoo+Tsh3nz2gAVz79eGrwzg9ZE=";
    }

    return getenv('ENCRYPTION_KEY');
}

$encryptedOption = new EncryptedOption(cds_default_get_encrypted_key());

$encryptedOptions = [
    'GITHUB_AUTH_TOKEN',
];

if (! Utils::isWpEnv()) {
    foreach ($encryptedOptions as $option) {
        add_filter("pre_update_option_{$option}", [$encryptedOption, 'encryptString']);
        add_filter("option_{$option}", [$encryptedOption, 'decryptString']);
    }
}

function cds_default_settings_init()
{
    register_setting(
        'cds_default_github_option_group',
        'GITHUB_AUTH_TOKEN',
        function ($input) {
            if ($input == '') {
                return get_option('GITHUB_AUTH_TOKEN');
            }
            return sanitize_text_field($input);
        }
    );

    // Register GitHub Repository URL setting
    register_setting(
        'cds_default_github_option_group',
        'GITHUB_REPOSITORY_URL',
        function ($input) {
            if ($input == '') {
                return get_option('GITHUB_REPOSITORY_URL', 'cds-snc/cds-website-pr-bot');
            }
            return sanitize_text_field($input);
        }
    );

    add_settings_section(
        'cds_default_github_section',
        __('GitHub integration', 'cds-snc'),
        'cds_default_settings_section_callback',
        'cds-default-settings-admin'
    );

    add_settings_field(
        'github_auth_token',
        __('GitHub Auth Token', 'cds-snc'),
        'cds_default_auth_token_callback',
        'cds-default-settings-admin',
        'cds_default_github_section',
        ['label_for' => 'github_auth_token']
    );

    // Repository URL field
    add_settings_field(
        'github_repository_url',
        __('GitHub Repository', 'cds-snc'),
        'cds_default_repository_callback',
        'cds-default-settings-admin',
        'cds_default_github_section',
        ['label_for' => 'github_repository_url']
    );
}

function cds_default_settings_section_callback($args)
{
    echo '<p>' . __('Configure GitHub webhook settings for triggering workflows when content is published.', 'cds-snc') . '</p>';
}

function cds_default_get_obfuscated_output_label($string, $labelId, $print = true)
{
    if (empty($string)) {
        return;
    }

    $startsWith = substr($string, 0, 4);
    $endsWith = substr($string, -4);

    $hint = sprintf(
        __('<span class="hidden_keys" id="%1$s">Current value: <span class="sr-only">Starts with </span>%2$s<span aria-hidden="true"> … </span><span class="sr-only"> and ends with</span>%3$s</span>', 'cds-snc'),
        $labelId,
        $startsWith,
        $endsWith
    );

    if ($print) {
        echo $hint;
        return;
    }

    return $hint;
}

function cds_default_auth_token_callback($args)
{
    $github_auth_token = get_option('GITHUB_AUTH_TOKEN');

    cds_default_get_obfuscated_output_label($github_auth_token, 'github_auth_token_value');

    printf(
        '<input class="regular-text" type="text" name="GITHUB_AUTH_TOKEN" id="github_auth_token" aria-describedby="github_auth_token_value" value="" placeholder="ghp_xxxxxxxxxxxxxxxxxxxx">'
    );
    echo '<p class="description">' . __('GitHub personal access token with repo permissions.', 'cds-snc') . '</p>';
}

function cds_default_repository_callback($args)
{
    $current_repo = get_option('GITHUB_REPOSITORY_URL', 'cds-snc/cds-website-pr-bot');
    
    printf(
        '<input class="regular-text" type="text" name="GITHUB_REPOSITORY_URL" id="github_repository_url" value="%s" placeholder="owner/repository-name">',
        esc_attr($current_repo)
    );
    echo '<p class="description">' . __('Format: owner/repository-name (e.g., cds-snc/my-project)', 'cds-snc') . '</p>';
}

function cds_default_add_styles()
{
    ?>
    <style type="text/css">
    .hidden_keys {
        padding-bottom: 3px;
        display: block;
        color: grey;
    }
    </style>
    <?php
}

function cds_default_options_page()
{
    add_options_page(
        __('CDS Default Settings', 'cds-snc'),
        __('CDS Default Settings', 'cds-snc'),
        'manage_options',
        'cds-default-settings',
        'cds_default_options_page_html'
    );
}

function cds_default_options_page_html()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php" id="cds_default_settings_form" class="gc-form-wrapper">
            <?php
            settings_fields('cds_default_github_option_group');
            do_settings_sections('cds-default-settings-admin');
            submit_button(__('Save Changes', 'cds-snc'));
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'cds_default_options_page', 100);
add_action('admin_init', 'cds_default_settings_init');
add_action('admin_head', 'cds_default_add_styles');