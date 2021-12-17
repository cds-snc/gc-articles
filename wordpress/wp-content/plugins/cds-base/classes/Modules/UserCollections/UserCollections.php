<?php

namespace CDS\Modules\UserCollections;

use WP_REST_Response;

class UserCollections
{
    public function __construct()
    {
        $this->addActions();
    }

    public function addActions()
    {
        add_action('rest_api_init', [$this, 'registerRestRoutes']);

        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function registerRestRoutes()
    {
        register_rest_route('usercollection', '/collections', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getUserCollections'],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);
    }

    public function getUserCollections(): WP_REST_Response
    {
        $uId = get_current_user_id();

        $blogs = get_blogs_of_user($uId);
        $current_blog_id = get_current_blog_id();

        $data = json_decode(json_encode($blogs), true);
        $data[$current_blog_id]['current'] = true;

        $response = new WP_REST_Response($data);

        $response->set_status(200);

        return $response;
    }

    public function dashboardWidget(): void
    {
        wp_add_dashboard_widget(
            'cds_collections_widget',
            __('Your collections', 'cds'),
            [$this, 'userCollectionsPanelHandler'],
        );
    }

    public function userCollectionsPanelHandler(): void
    {
        echo '<div id="collections-panel"></div>';
        $data = 'CDS.renderCollectionsPanel();';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
