<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package cds-default
 */

declare(strict_types=1);

get_header();
?>

    <main id="primary" property="mainContentOfPage" class="search container" resource="#wb-main" typeof="WebPageElement">

        <?php if (have_posts()) { ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query. */
                    printf(esc_html__('Search Results for: %s', 'cds-snc'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
            </header><!-- .page-header -->

            <?php
            /* Start the Loop */
            while (have_posts()) {
                the_post();

                /**
                 * Run the loop for the search to output the results.
                 * If you want to overload this in a child theme then include a file
                 * called content-search.php and that will be used instead.
                 */
                get_template_part('template-parts/content', 'search');
            }

            cds_the_posts_navigation();
        } else {
            get_template_part('template-parts/content', 'none');
        }
        ?>

    </main><!-- #main -->

<?php
get_footer();
