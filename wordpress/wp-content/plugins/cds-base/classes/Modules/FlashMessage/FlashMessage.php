<?php

declare(strict_types=1);

namespace CDS\Modules\FlashMessage;

/**
 * Flash message
 * Adapted from: https://github.com/dgrundel/wp-flash-messages
 * Queue flash messages for display on next page load. Messages are displayed as admin_notices and immediately
 * cleared. This module uses PHP sessions to store messages, and as such you should probably use wp-native-php-sessions
 * plugin for this all to work properly in a distributed environment.
 */
class FlashMessage
{
    const WP_FLASH_MESSAGES = 'wp_flash_messages';

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        add_action('admin_notices', [$this, 'showFlashMessages'], 10);
        add_action('network_admin_notices', [$this, 'showFlashMessages'], 10);
    }

    /**
     * @param $message
     * @param  string  $class
     * Push Flash messages onto an Options array
     */
    public static function queueFlashMessage($message, $class = '')
    {
        $default_allowed_classes = ['error', 'updated'];

        $allowed_classes = apply_filters('flash_messages_allowed_classes', $default_allowed_classes);
        $default_class = apply_filters('flash_messages_default_class', 'updated');

        if (!in_array($class, $allowed_classes)) {
            $class = $default_class;
        }

        $flash_messages = maybe_unserialize(self::getMessages());
        $flash_messages[$class][] = $message;

        self::updateMessages($flash_messages);
    }

    /**
     * Display Flash messages
     */
    public static function showFlashMessages()
    {
        // get serialized messages from session and unserialize
        $flash_messages = maybe_unserialize(self::getMessages());

        if (is_array($flash_messages)) {
            foreach ($flash_messages as $class => $messages) {
                foreach ($messages as $message) {
                    ?><div class="<?php echo $class; ?>"><p><?php echo $message; ?></p></div><?php
                }
            }
        }

        // clear flash messages after display
        self::clearMessages();
    }

    protected static function getMessages()
    {
        return $_SESSION[self::WP_FLASH_MESSAGES] ?? [];
    }

    protected static function updateMessages($messages)
    {
        $_SESSION[self::WP_FLASH_MESSAGES] = serialize($messages);
    }

    protected static function clearMessages()
    {
        unset($_SESSION[self::WP_FLASH_MESSAGES]);
    }
}