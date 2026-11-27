<?php

declare(strict_types=1);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    if (is_singular()) {
        the_title('<gcds-heading tag="h1">', '</gcds-heading>');
    } else {
        the_title('<gcds-heading tag="h2"><gcds-link href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</gcds-link></gcds-heading>');
    }
    cds_post_thumbnail();
    ?>
    <div id="content-<?php the_ID(); ?>" class="entry-content">
        <?php
        if (is_singular()) {
            the_content();
        } else {
            echo cds_gcds_convert_html(apply_filters('the_excerpt', get_the_excerpt()));
        }
        if (get_post_type() === 'post') {
            cds_posted_on();
        }
        ?>
    </div>
    <?php cds_entry_footer(); ?>
</article>
