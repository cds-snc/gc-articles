<?php

declare(strict_types=1);

$side_nav = get_side_nav();
get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <?php if ($side_nav) : ?>
        <gcds-grid columns="1fr" columns-tablet="1fr 3fr">
            <aside><?php echo $side_nav; ?></aside>
            <div>
    <?php endif; ?>
    <?php
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', 'page');
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }
    ?>
    <?php if ($side_nav) : ?>
            </div>
        </gcds-grid>
    <?php endif; ?>
    <?php cds_last_modified_date(); ?>
</gcds-container>
<?php get_footer(); ?>
