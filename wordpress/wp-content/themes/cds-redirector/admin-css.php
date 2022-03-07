<?php
function load_admin_style() {
    wp_enqueue_style( 'redirector_admin_css', get_template_directory_uri() . '/redirector-admin.css', false, '1.0.0' );
}

add_action( 'admin_enqueue_scripts', 'load_admin_style' );