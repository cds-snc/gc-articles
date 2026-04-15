<?php

function cds_default_onSavePost($post_ID, $post)
{
    if ($post->post_status === "publish") {
        try {
            // Get configurable repository URL
            $repo_url = get_option('GITHUB_REPOSITORY_URL');
            $url = "https://api.github.com/repos/{$repo_url}/dispatches";
            $token = get_option('GITHUB_AUTH_TOKEN');

            if (empty($token)) {
                error_log("GitHub :: No auth token configured");
                return;
            }

            if (empty($repo_url)) {
                error_log("GitHub :: No repository URL configured");
                return;
            }

            $args = [
                'headers' => [
                    'Accept' => 'application/vnd.github+json',
                    'Authorization' => 'token ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode(['event_type' => 'strapi_update'])
            ];

            $response = wp_remote_post($url, $args);

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                error_log("Github :: Something went wrong: $error_message");
            } else {
                error_log("Github :: Success - triggered workflow for: $repo_url");
                error_log(print_r($response['response'], true));
            }
        } catch (Exception $e) {
            error_log('Caught exception: ' . $e->getMessage() . "\n");
        }
    }
}

add_action('save_post', 'cds_default_onSavePost', 10, 2);
