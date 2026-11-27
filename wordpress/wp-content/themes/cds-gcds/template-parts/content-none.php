<section class="no-results">
    <gcds-heading tag="h1"><?php esc_html_e('Nothing Found', 'cds-snc'); ?></gcds-heading>
    <?php if (is_home() && current_user_can('publish_posts')) : ?>
        <gcds-text><?php esc_html_e('Ready to publish your first post?', 'cds-snc'); ?></gcds-text>
        <gcds-link href="<?php echo esc_url(admin_url('post-new.php')); ?>"><?php esc_html_e('Get started here', 'cds-snc'); ?></gcds-link>
    <?php elseif (is_search()) : ?>
        <gcds-text><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cds-snc'); ?></gcds-text>
        <?php get_search_form(); ?>
    <?php else : ?>
        <gcds-text><?php esc_html_e('Nothing found! Perhaps searching can help.', 'cds-snc'); ?></gcds-text>
        <?php get_search_form(); ?>
    <?php endif; ?>
</section>
