<?php

declare(strict_types=1);

use CDS\Modules\Site\SiteSettings;

$lang = get_active_language();
$footerText = require __DIR__ . '/footer_text_' . $lang . '.php';

?>

<div hidden id="version"><?php echo esc_html(_S_VERSION); ?></div>
<?php
$locations = get_nav_menu_locations();
$links = $footerText['aboutThisSite'];
if (isset($locations['footer'])) {
    $links = wp_get_nav_menu_items($locations['footer']) ?: [];
}
$subLinks = new stdClass();
if (!cds_is_maintenance_mode()) {
    foreach ($links as $link) {
        $link = (object) $link;
        $subLinks->{$link->title} = esc_url($link->url);
    }
}
$contextualLinks = new stdClass();
foreach ($footerText['aboutGovernment'] as $link) {
    $contextualLinks->{$link['title']} = esc_url($link['url']);
}
?>
<gcds-footer display="full" contextual-heading="<?php echo esc_attr($footerText['aboutGovernmentTitle']); ?>"
    contextual-links='<?php echo esc_attr(wp_json_encode($contextualLinks)); ?>'
    sub-links='<?php echo esc_attr(wp_json_encode($subLinks)); ?>'></gcds-footer>

<?php wp_footer(); ?>

<?php
$analyticsId = get_option('analytics_id');
$googleTagManagerId = get_option('google_tag_manager_id');

// If we have a Google Tag Manager ID, use that. Otherwise, use Google Analytics.
if ($googleTagManagerId) {
    $gtm_code_id = $googleTagManagerId;

    if (SiteSettings::isValidTrackingId('google_tag_manager_id', $gtm_code_id)) {
        ob_start(); ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo esc_js($gtm_code_id); ?>');</script>
        <!-- End Google Tag Manager -->
        <?php
        $gtm_code = ob_get_contents();
        ob_end_clean();
        echo $gtm_code;
    }
} else if ($analyticsId) {
    $ga_code_id = $analyticsId;

    if (SiteSettings::isValidTrackingId('analytics_id', $ga_code_id)) {
        ob_start(); ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="<?php echo esc_url('https://www.googletagmanager.com/gtag/js?id=' . rawurlencode($ga_code_id)); ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($ga_code_id); ?>');
        </script>
        <?php
        $ga_code = ob_get_contents();
        ob_end_clean();
        echo $ga_code;
    }
}
?>
</body>
</html>
