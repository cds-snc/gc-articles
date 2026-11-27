<?php

declare(strict_types=1);

get_header();
?>
<gcds-container id="primary" tag="main" layout="page" class="py-400" tabindex="-1">
    <gcds-heading tag="h1"><?php esc_html_e('Page not found.', 'cds-snc'); ?></gcds-heading>
    <gcds-text><?php esc_html_e('It looks like nothing was found at this location.', 'cds-snc'); ?></gcds-text>
</gcds-container>
<?php get_footer(); ?>
