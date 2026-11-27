<?php

declare(strict_types=1);

get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <?php
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content');
        cds_prev_next_links();
    }
    cds_last_modified_date();
    ?>
</gcds-container>
<?php get_footer(); ?>
