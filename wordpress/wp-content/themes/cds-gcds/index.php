<?php

declare(strict_types=1);

get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <?php
    if (have_posts()) {
        $title = is_home() && !is_front_page() ? single_post_title('', false) : get_bloginfo('name');
        echo '<gcds-heading tag="h1">' . esc_html($title) . '</gcds-heading>';
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/content');
        }
        cds_the_posts_navigation();
    } else {
        get_template_part('template-parts/content', 'none');
    }
    cds_last_modified_date();
    ?>
</gcds-container>
<?php get_footer(); ?>
