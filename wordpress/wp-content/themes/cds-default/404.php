<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package cds-default
 */

declare(strict_types=1);

get_header();
?>
 <main id="error-404 not-found" property="mainContentOfPage" class="index container" resource="#wb-main" typeof="WebPageElement"> 
        
            <header class="page-header" id="primary">
                <h1 class="page-title"><?php esc_html_e(
                    'Page not found.',
                    'cds-snc',
); ?></h1>
            </header><!-- .page-header -->

            
                <p><?php esc_html_e(
                    'It looks like nothing was found at this location.',
                    'cds-snc',
); ?></p>
            
      

    </main><!-- #main -->

<?php get_footer();
