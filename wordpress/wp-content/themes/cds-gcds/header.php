<?php

declare(strict_types=1);

?>
<!doctype html>
<html lang="<?php echo esc_attr(get_active_language()); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <meta name="rest_url" content="<?php echo esc_url(rest_url()); ?>">
    <meta name="rest_nonce" content="<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<gcds-header lang-href="<?php echo esc_url(cds_gcds_language_url()); ?>" skip-to-href="#primary">
    <gcds-link slot="signature" href="<?php echo esc_url(get_fip_url()); ?>">
        <gcds-signature></gcds-signature>
    </gcds-link>
    <?php if (cds_is_maintenance_mode_admin_user()) : ?>
        <gcds-container slot="banner" layout="page">
            <gcds-notice notice-role="warning" notice-title="PRIVATE" notice-title-tag="h2">
                <gcds-text><?php esc_html_e('Your site is currently in maintenance mode. Only logged in users will be able to see this page.', 'cds-snc'); ?></gcds-text>
                <gcds-link href="<?php echo esc_url(admin_url('options-general.php?page=collection-settings')); ?>"><?php esc_html_e('Site settings', 'cds-snc'); ?></gcds-link>
            </gcds-notice>
        </gcds-container>
    <?php endif; ?>
    <gcds-search slot="search" search-id="site-search" name="s" method="get"
        action="<?php echo esc_url(home_url('/')); ?>"
        placeholder="<?php echo esc_attr(get_bloginfo('name')); ?>"
        value="<?php echo esc_attr(get_search_query(false)); ?>"></gcds-search>
    <div slot="account" class="d-flex flex-wrap gap-200">
        <?php echo cds_gcds_menu_links('topbar'); ?>
    </div>
    <?php
    $menu = get_top_nav();
    $setting = get_option('show_wet_menu');
    if ((empty($setting) || $setting === 'custom') && $menu && !cds_is_maintenance_mode()) {
        echo $menu;
    } else {
        echo '<gcds-topic-menu slot="menu"></gcds-topic-menu>';
    }
    echo cds_breadcrumb();
    ?>
</gcds-header>
