<?php

declare(strict_types=1);

function cds_change_post_label(): void
{
    global $menu;
    global $submenu;
    $menu[5][0] = __('Articles', 'cds');
    $submenu['edit.php'][5][0] = __('Articles', 'cds');
    $submenu['edit.php'][10][0] = __('Add Article', 'cds');
    $submenu['edit.php'][16][0] = __('Article Tags', 'cds');
}

function cds_change_post_object(): void
{
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = __('Articles', 'cds');
    $labels->singular_name = __('Articles', 'cds');
    $labels->add_new = __('Add Articles', 'cds');
    $labels->add_new_item = __('Add Articles', 'cds');
    $labels->edit_item = __('Edit Articles', 'cds');
    $labels->new_item = __('Articles', 'cds');
    $labels->view_item = __('View Articles', 'cds');
    $labels->search_items = __('Search Articles', 'cds');
    $labels->not_found = __('No Articles found', 'cds');
    $labels->not_found_in_trash = __('No Articles found in Trash', 'cds');
    $labels->all_items = __('All Articles', 'cds');
    $labels->menu_name = __('Articles', 'cds');
    $labels->name_admin_bar = __('Articles', 'cds');
}

add_action('admin_menu', 'cds_change_post_label');
add_action('init', 'cds_change_post_object');
