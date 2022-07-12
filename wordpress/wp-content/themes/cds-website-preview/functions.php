<?php

add_theme_support('post-thumbnails');

function cds_web_register_post_type()
{
    $args = [
        'label'  => esc_html__('Products', 'text-domain'),
        'labels' => [
            'menu_name'          => esc_html__('Products', 'cds-web'),
            'name_admin_bar'     => esc_html__('Product', 'cds-web'),
            'add_new'            => esc_html__('Add Product', 'cds-web'),
            'add_new_item'       => esc_html__('Add new Product', 'cds-web'),
            'new_item'           => esc_html__('New Product', 'cds-web'),
            'edit_item'          => esc_html__('Edit Product', 'cds-web'),
            'view_item'          => esc_html__('View Product', 'cds-web'),
            'update_item'        => esc_html__('View Product', 'cds-web'),
            'all_items'          => esc_html__('All Products', 'cds-web'),
            'search_items'       => esc_html__('Search Products', 'cds-web'),
            'parent_item_colon'  => esc_html__('Parent Product', 'cds-web'),
            'not_found'          => esc_html__('No Products found', 'cds-web'),
            'not_found_in_trash' => esc_html__('No Products found in Trash', 'cds-web'),
            'name'               => esc_html__('Products', 'cds-web'),
            'singular_name'      => esc_html__('Product', 'cds-web'),
        ],
        'public'              => true,
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
        'show_in_menu'        => false,
        'supports' => [
            'title',
            'editor',
            'thumbnail',
        ],

        'rewrite' => true
    ];

    register_post_type('product', $args);
}

add_action('init', 'cds_web_register_post_type');

//

function onSavePost($post_ID, $post)
{
    // note this will fire for saves and updates
    if ($post->post_status === "publish") {
        $hostname = "https://api.github.com";
        $path = "repos/cds-snc/cds-website-pr-bot/dispatches";

        //$path = "repos/cds-snc/cds-website-pr-bot/languages";

        $url = $hostname . '/' . $path;
        $token = "";

        $args = [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'token ' . $token,
            ],
            'body'        => [
                'event_type' => 'strapi_update',
                "client_payload" => json_encode(["test" => "true"])
            ]
        ];

        error_log(print_r($args, true));
        /*
        $response = wp_remote_post($url);

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            error_log( "Something went wrong: $error_message");
        } else {
            error_log('Response:');
            error_log($response['body']);
            error_log(print_r( $response, true ));
        }
        */
    }
}

add_action('save_post', 'onSavePost', 10, 2);
