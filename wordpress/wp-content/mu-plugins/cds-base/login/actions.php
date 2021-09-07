<?php
add_action( 'wp_login_failed', 'login_failed' );

function login_failed( $username ) {
    error_log("LOGIN FAILED: user $username: authentication failure for \"".admin_url()."\"");
}