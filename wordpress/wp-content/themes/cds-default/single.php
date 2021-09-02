<?php

declare(strict_types=1);

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cds-default
 */

get_header();
?>

    <main property="mainContentOfPage" class="single container" resource="#wb-main" typeof="WebPageElement">

        <?php
        while (have_posts()) {
            the_post();

            get_template_part('template-parts/content', get_post_type());

            cds_prev_next_links();

            /*
            the_post_navigation(
                [
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'cds-snc') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'cds-snc') . '</span> <span class="nav-title">%title</span>',
                ]
            );
            */
        } // End of the loop.
        ?>

    </main><!-- #main -->

<?php
get_footer();
