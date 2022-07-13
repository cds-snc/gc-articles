<?php

use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Utils;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Setup EncryptedOption.
 * There is some duplicated code here from cds-base/classes/Setup.php because that class
 * does not use the singleton pattern we use elsewhere. If we refactor that class, we could remove some of this
 * duplicate code and retrieve the initialized EncryptedOption from there.
 */
function getEncryptionKey()
{
    /**
     * If we're in a wp-env dev, test, or cli environment, return a hard-coded key. This works because the
     * environment variable is not available in the wp-env environment, but is available in our docker cli.
     */
    if ((Utils::isWpEnv()) || (defined('WP_CLI') && WP_CLI)) {
        return getenv('ENCRYPTION_KEY') ?: "base64:cELNoBToBqa9NtubmEoo+Tsh3nz2gAVz79eGrwzg9ZE=";
    }

    return getenv('ENCRYPTION_KEY');
}

$encryptedOption = new EncryptedOption(getEncryptionKey());

$encryptedOptions = [
    'GITHUB_AUTH_TOKEN',
];

if (!\CDS\Utils::isWpEnv()) {
    foreach ($encryptedOptions as $option) {
        add_filter("pre_update_option_{$option}", [$encryptedOption, 'encryptString']);
        add_filter("option_{$option}", [$encryptedOption, 'decryptString']);
    }
}

function cds_web_settings_init()
{
    register_setting(
        'github_auth_token_option_group', // option_group
        'GITHUB_AUTH_TOKEN',
        function ($input) {
            if ($input == '') {
                return get_option('GITHUB_AUTH_TOKEN');
            }

            return sanitize_text_field($input);
        }
    );

    add_settings_section(
        'cds_web_settings_setting_section', // id
        __('Github integration', 'cds-snc'), // title
        'cds_web_settings_section_callback', // callback
        'cds-web-settings-admin' // page
    );

    add_settings_field(
        'github_auth_token', // id
        __('Auth Token', 'cds-snc'), // title
        'cds_web_auth_token_callback', // callback
        'cds-web-settings-admin', // page
        'cds_web_settings_setting_section', // section
        [
            'label_for' => 'github_auth_token'
        ]
    );
}

function cds_web_settings_section_callback($args)
{
}

function getObfuscatedOutputLabel($string, $labelId, $print = true)
{
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

function cds_web_auth_token_callback($args)
{
    $github_auth_token = get_option('GITHUB_AUTH_TOKEN');

    getObfuscatedOutputLabel($github_auth_token, 'github_auth_token_value');

    printf(
        '<input class="regular-text" type="text" name="GITHUB_AUTH_TOKEN" id="github_auth_token" aria-describedby="github_auth_token_value" value="">'
    );
}

function cds_web_add_styles()
{
    ?><style type="text/css">
    .hidden_keys {
        padding-bottom: 3px;
        display: block;
        color: grey;
    }
</style><?php
}

function cds_web_options_page()
{
    add_options_page(
        __('CDS Website Settings', 'cds-snc'), // page_title
        __('CDS Website Settings', 'cds-snc'), // menu_title
        'manage_options', // capability
        'cds-web-settings', // menu_slug
        'cds_web_options_page_html' // function
    );
}

function cds_web_options_page_html()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php" id="notify_settings_form" class="gc-form-wrapper">
            <?php
            settings_fields('github_auth_token_option_group');
            do_settings_sections('cds-web-settings-admin');
            submit_button(__('Save Changes', 'cds-snc'));
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'cds_web_options_page', 100);
add_action('admin_init', 'cds_web_settings_init');
add_action('admin_head', 'cds_web_add_styles');