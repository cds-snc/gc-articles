<?php

include get_theme_file_path('/inc/webhook.php');
include get_theme_file_path('/post-types/job.php');
include get_theme_file_path('/post-types/team.php');
include get_theme_file_path('/post-types/product.php');

function get_fonts_uri()
{
    return get_stylesheet_directory_uri() . "/fonts/";
}

add_theme_support('post-thumbnails');

require_once __DIR__ . '/filter-core-image.php';
require_once(__DIR__ . '/settings.php');
