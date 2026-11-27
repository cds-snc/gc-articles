<?php

declare(strict_types=1);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title('<gcds-heading tag="h2"><gcds-link href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</gcds-link></gcds-heading>'); ?>
    <?php
    if (get_post_type() === 'post') {
        cds_posted_on();
        cds_posted_by();
    }
    cds_post_thumbnail();
    ?>
    <div class="entry-summary">
        <?php echo cds_gcds_convert_html(apply_filters('the_excerpt', get_the_excerpt())); ?>
    </div>
    <?php cds_entry_footer(); ?>
</article>
