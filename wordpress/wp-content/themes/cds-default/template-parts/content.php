<?php

declare(strict_types=1);

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cds-default
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) {
            the_title('<h1 class="gc-thickline">', '</h1>');
        } else {
            the_title('<h2 class="gc-thickline"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        }
        ?>
    </header><!-- .entry-header -->

    <?php cds_post_thumbnail(); ?>

    <div id="content" class="entry-content template-part-content">
        <?php
         if (is_singular()) {
             the_content(
                 sprintf(
                     wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'cds-snc'),
                         [
                             'span' => [
                                 'class' => [],
                             ],
                         ]
                     ),
                     wp_kses_post(get_the_title())
                 )
             );
         } else {
             wp_trim_excerpt(the_excerpt());
         }

        if (get_post_type() === 'post') {
            ?>
             <div class="entry-meta">
                [<?php cds_posted_on(); ?>]
            </div><!-- .entry-meta -->
        <?php
        }
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php cds_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->