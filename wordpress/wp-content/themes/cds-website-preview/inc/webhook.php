<?php

function onSavePost($post_ID, $post)
{
    // note this will fire for saves and updates
    if ($post->post_status === "publish") {
        $url = "https://api.github.com/repos/cds-snc/cds-website-pr-bot/dispatches";
        $token = "";
        $args = [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'token ' . $token,
                'Content-Type' => 'application/json'
            ],
            'body'        => json_encode(['event_type' => 'strapi_update'])
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            error_log("Github :: Something went wrong: $error_message");
        } else {
            error_log('Github :: response:');
            error_log(print_r($response['response'], true));
        }
    }
}

add_action('save_post', 'onSavePost', 10, 2);
