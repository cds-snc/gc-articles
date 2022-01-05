<?php

declare(strict_types=1);

$title = _("We're currently working on this", "cds-snc");
$content = "";
$maintenance_page_id = get_option('collection_mode_maintenance_page');

if ($maintenance_page_id) {
    // use this page for maintenance page content
    $title = get_the_title($maintenance_page_id);
    $content = apply_filters('the_content', get_the_content(null, null, $maintenance_page_id));
}

add_filter("pre_get_document_title",'title_callback');

function title_callback(){
    global $title;
    return $title;
}

get_header();
?>

    <main id="primary" property="mainContentOfPage" class="index container" resource="#wb-main" typeof="WebPageElement">
       <h1><?php echo $title;?></h1>
       <?php echo $content; ?>
    </main><!-- #main -->

<?php
get_footer();