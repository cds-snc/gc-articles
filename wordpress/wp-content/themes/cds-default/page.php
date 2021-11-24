<?php

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

declare(strict_types=1);

$side_nav = getSideNav();
$has_side_nav = !empty($side_nav) ? true : false;

get_header();
?>

    <main property="mainContentOfPage" class="page container" resource="#wb-main" typeof="WebPageElement">

        <?php if ($has_side_nav) { ?>
        <div class="wp-block-columns">
            <div class="wp-block-column page__side-nav">   
                <?php echo($side_nav); ?>
            </div>
            <div class="wp-block-column page__content">   
        <?php } ?>

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
        <?php if ($has_side_nav) { ?>
            </div>
            </div><!--end of .wp-block-columns -->
        <?php } ?>

    </main><!-- #main -->

<?php
get_footer();
