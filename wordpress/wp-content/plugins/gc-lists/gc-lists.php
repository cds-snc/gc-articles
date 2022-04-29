<?php

/**
 * Plugin Name:     GC Lists
 * Description:     Plugin for GC Lists
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     gc-lists
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Gc_Lists
 */

use GCLists\GCLists;

if (is_readable(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

add_action('plugins_loaded', [GCLists::class, 'register']);
register_activation_hook(__FILE__, [GCLists::class, 'install']);
