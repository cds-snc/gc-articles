<?php

/**
 * Notify API Key not configured alert
 *
 * @var string $title
 */
?>

<h1><?php echo $title; ?></h1>

<p>
    <?php
        echo sprintf(
            __('You must configure your <a href="%s">Notify API Key</a>', 'gc-lists'),
            admin_url("options-general.php?page=notify-settings")
        );
        ?>
</p>
