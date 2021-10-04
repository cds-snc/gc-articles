<?php

declare(strict_types=1);

namespace CDS\Modules\FlashMessage;

/**
 * Flash messages
 * Adapted from: https://github.com/dgrundel/wp-flash-messages
 * Queue flash messages for display on next page load. Messages are displayed as admin_notices and immediately
 * cleared.
 */
class FlashMessage
{
    public function __construct() {
        add_action('admin_notices', [$this, 'show_flash_messages'], 1001);
        add_action('network_admin_notices', [$this, 'show_flash_messages'], 1001);
    }

    /**
     * @param $message
     * @param  string  $class
     * Push Flash messages onto an Options array
     */
    public static function queue_flash_message($message, $class = '') {
        $default_allowed_classes = ['error', 'updated'];

        $allowed_classes = apply_filters('flash_messages_allowed_classes', $default_allowed_classes);
        $default_class = apply_filters('flash_messages_default_class', 'updated');

        if(!in_array($class, $allowed_classes)) $class = $default_class;

        $flash_messages = maybe_unserialize(get_option('wp_flash_messages', []));
        $flash_messages[$class][] = $message;

        update_option('wp_flash_messages', $flash_messages);
    }

    /**
     * Display Flash messages
     */
    public static function show_flash_messages() {
        $flash_messages = maybe_unserialize(get_option('wp_flash_messages', ''));

        if(is_array($flash_messages)) {
            foreach($flash_messages as $class => $messages) {
                foreach($messages as $message) {
                    ?><div class="<?php echo $class; ?>"><p><?php echo $message; ?></p></div><?php
                }
            }
        }

        // clear flash messages after display
        delete_option('wp_flash_messages');
    }
}