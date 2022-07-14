<?php

function cds_web_register_team_member_type()
{
    $args = [
        'label'  => esc_html__('Team Members', 'cds-web'),
        'template' => [['cds/team' ]],
        'labels' => [
            'menu_name'          => esc_html__('Team Members', 'cds-web'),
            'name_admin_bar'     => esc_html__('Team Member', 'cds-web'),
            'add_new'            => esc_html__('Add Team Member', 'cds-web'),
            'add_new_item'       => esc_html__('Add new Team Member', 'cds-web'),
            'new_item'           => esc_html__('New Team Member', 'cds-web'),
            'edit_item'          => esc_html__('Edit Team Member', 'cds-web'),
            'view_item'          => esc_html__('View Team Member', 'cds-web'),
            'update_item'        => esc_html__('View Team Member', 'cds-web'),
            'all_items'          => esc_html__('All Team Members', 'cds-web'),
            'search_items'       => esc_html__('Search Team Members', 'cds-web'),
            'parent_item_colon'  => esc_html__('Parent Team Member', 'cds-web'),
            'not_found'          => esc_html__('No Team Members found', 'cds-web'),
            'not_found_in_trash' => esc_html__('No Team Members found in Trash', 'cds-web'),
            'name'               => esc_html__('Team Members', 'cds-web'),
            'singular_name'      => esc_html__('Team Member', 'cds-web'),
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

    register_post_type('team', $args);
}

add_action('init', 'cds_web_register_team_member_type');
