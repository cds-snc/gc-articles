<?php

declare(strict_types=1);

$maintenance_page_id = get_option('collection_mode_maintenance_page');
$title = $maintenance_page_id ? get_the_title($maintenance_page_id) : __("We’re currently working on this", 'cds-snc');
$content = $maintenance_page_id ? apply_filters('the_content', get_the_content(null, null, $maintenance_page_id)) : '';
add_filter('pre_get_document_title', static fn() => $title);
get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <gcds-heading tag="h1"><?php echo esc_html($title); ?></gcds-heading>
    <?php echo $content; ?>
</gcds-container>
<?php get_footer(); ?>
