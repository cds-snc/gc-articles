<?php

declare(strict_types=1);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title('<gcds-heading tag="h1" id="wb-cont">', '</gcds-heading>'); ?>
    <?php cds_post_thumbnail(); ?>
    <div id="content" class="entry-content">
        <?php the_content(); ?>
        <?php echo cds_gcds_convert_html(wp_link_pages(['echo' => false])); ?>
    </div>
    <?php if (get_edit_post_link()) : ?>
        <gcds-link href="<?php echo esc_url(get_edit_post_link()); ?>">
            <?php esc_html_e('Edit', 'cds-snc'); ?>
            <gcds-sr-only tag="span"><?php echo esc_html(get_the_title()); ?></gcds-sr-only>
        </gcds-link>
    <?php endif; ?>
</article>
