<?php

/**
 * Notify API Key not configured alert
 *
 * @var string $title
 * @var array $missingValues
 */

$missingValuesText = [
    'NOTIFY_API_KEY' => __('your Notify API key', 'gc-lists'),
    'NOTIFY_GENERIC_TEMPLATE_ID' => __('a Notify email template ID'),
    'NOTIFY_SUBSCRIBE_TEMPLATE_ID' => __('a Notify subscription template ID')
]
?>

<h1><?php echo $title; ?></h1>

<p>
    <?php
        echo sprintf(
            __('You must configure your <a href="%s">Notify API Settings</a>.', 'gc-lists'),
            admin_url("/wp-admin/admin.php?page=settings")
        );
        echo " ";
        _e('Please provide:', 'gc-lists');
        ?>
</p>
<ul style="font-size: 16px; list-style: disc inside;">
    <?php
    foreach ($missingValues as $missingValue) {
        echo "<li>$missingValuesText[$missingValue]</li>";
    }
    ?>
</ul>
<p>
    <?php _e('If you need help with this, you can reach out to <a href="mailto:platform-mvp@cds-snc.ca">platform-mvp@cds-snc.ca</a> for support.', 'gc-lists'); ?>
</p>
