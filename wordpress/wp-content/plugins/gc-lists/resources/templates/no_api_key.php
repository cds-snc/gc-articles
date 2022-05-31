<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

<p>
    <?php
    echo sprintf(
        __('You must configure your <a href="%s">Notify API Key</a>', 'cds-snc'),
        admin_url("options-general.php?page=notify-settings")
    );
    ?>
</p>
