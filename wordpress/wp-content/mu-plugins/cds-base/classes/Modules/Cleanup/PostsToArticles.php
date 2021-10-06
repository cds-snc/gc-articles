<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class PostsToArticles
{
    public function __construct()
    {
        add_action('init', [$this, 'changePostObject'], 99);
    }

    public function changePostObject(): void
    {
        try {
            if (!current_user_can("edit_posts")) {
                return;
            }

            global $wp_post_types;
            $labels = &$wp_post_types['post']->labels;
            $labels->name = __('Articles', 'cds-snc');
            $labels->singular_name = __('Article', 'cds-snc');
            $labels->add_new = __('Add Article', 'cds-snc');
            $labels->add_new_item = __('Add Article', 'cds-snc');
            $labels->edit_item = __('Edit Article', 'cds-snc');
            $labels->new_item = __('Article', 'cds-snc');
            $labels->view_item = __('View Article', 'cds-snc');
            $labels->search_items = __('Search Articles', 'cds-snc');
            $labels->not_found = __('No Articles found', 'cds-snc');
            $labels->not_found_in_trash = __('No Articles found in Trash', 'cds-snc');
            $labels->all_items = __('All Articles', 'cds-snc');
            $labels->menu_name = __('Articles', 'cds-snc');
            $labels->name_admin_bar = __('Article', 'cds-snc');
        } catch (Exception $e) {
            error_log("post menu not found");
        }
    }
}
