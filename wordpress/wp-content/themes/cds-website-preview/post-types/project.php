<?php

function cds_web_register_project_type()
{
    $args = [
        'label'  => esc_html__('Projects', 'text-domain'),
        'labels' => [
            'menu_name'          => esc_html__('Projects', 'cds-web'),
            'name_admin_bar'     => esc_html__('Project', 'cds-web'),
            'add_new'            => esc_html__('Add Project', 'cds-web'),
            'add_new_item'       => esc_html__('Add new Project', 'cds-web'),
            'new_item'           => esc_html__('New Project', 'cds-web'),
            'edit_item'          => esc_html__('Edit Project', 'cds-web'),
            'view_item'          => esc_html__('View Project', 'cds-web'),
            'update_item'        => esc_html__('View Project', 'cds-web'),
            'all_items'          => esc_html__('All Projects', 'cds-web'),
            'search_items'       => esc_html__('Search Projects', 'cds-web'),
            'parent_item_colon'  => esc_html__('Parent Project', 'cds-web'),
            'not_found'          => esc_html__('No Projects found', 'cds-web'),
            'not_found_in_trash' => esc_html__('No Projects found in Trash', 'cds-web'),
            'name'               => esc_html__('Projects', 'cds-web'),
            'singular_name'      => esc_html__('Project', 'cds-web'),
        ],
        'public'              => true,
        'menu_icon' => 'dashicons-format-gallery',
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

    register_post_type('project', $args);
}

add_action('init', 'cds_web_register_project_type');
