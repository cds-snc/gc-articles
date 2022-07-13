<?php

function cds_web_register_post_type()
{
    $args = [
        'label'  => esc_html__('Jobs', 'text-domain'),
        'labels' => [
            'menu_name'          => esc_html__('Jobs', 'cds-web'),
            'name_admin_bar'     => esc_html__('Job', 'cds-web'),
            'add_new'            => esc_html__('Add Job', 'cds-web'),
            'add_new_item'       => esc_html__('Add new Job', 'cds-web'),
            'new_item'           => esc_html__('New Job', 'cds-web'),
            'edit_item'          => esc_html__('Edit Job', 'cds-web'),
            'view_item'          => esc_html__('View Job', 'cds-web'),
            'update_item'        => esc_html__('View Job', 'cds-web'),
            'all_items'          => esc_html__('All Jobs', 'cds-web'),
            'search_items'       => esc_html__('Search Jobs', 'cds-web'),
            'parent_item_colon'  => esc_html__('Parent Job', 'cds-web'),
            'not_found'          => esc_html__('No Jobs found', 'cds-web'),
            'not_found_in_trash' => esc_html__('No Jobs found in Trash', 'cds-web'),
            'name'               => esc_html__('Jobs', 'cds-web'),
            'singular_name'      => esc_html__('Job', 'cds-web'),
        ],
        'public'              => true,
        'menu_icon' => 'dashicons-megaphone',
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite_no_front'    => false,
        'show_in_menu'        => true,
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'custom-fields'
        ],

        'rewrite' => true
    ];

    register_post_type('job', $args);
}

add_action('init', 'cds_web_register_post_type');
