<?php

declare(strict_types=1);

get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <?php
    if (have_posts()) {
        echo '<gcds-heading tag="h1">' . sprintf(esc_html__('Search Results for: %s', 'cds-snc'), esc_html(get_search_query(false))) . '</gcds-heading>';
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/content', 'search');
        }
        cds_the_posts_navigation();
    } else {
        get_template_part('template-parts/content', 'none');
    }
    cds_last_modified_date();
    ?>
</gcds-container>
<?php get_footer(); ?>
