<?php

add_theme_support('post-thumbnails');

# https://github.com/cds-snc/cds-website-cms/blob/main/api/webhooks/controllers/webhooks.js#L9-L39

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
