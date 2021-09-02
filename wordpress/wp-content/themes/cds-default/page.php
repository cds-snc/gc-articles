<?php

declare(strict_types=1);

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cds-default
 */

get_header();
?>

    <main property="mainContentOfPage" class="page container" resource="#wb-main" typeof="WebPageElement">

        <?php
        while (have_posts()) {
            the_post();

            get_template_part('template-parts/content', 'page');

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        } // End of the loop.
        ?>

    </main><!-- #main -->

<?php
get_footer();
