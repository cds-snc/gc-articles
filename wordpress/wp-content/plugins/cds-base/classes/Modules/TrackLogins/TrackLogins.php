<?php

namespace CDS\Modules\TrackLogins;

use Carbon\Carbon;
use UAParser\Parser;
use WP_REST_Response;
use CDS\Utils;

class TrackLogins
{
    protected $tableName;
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb      = $wpdb;

        if (property_exists($wpdb, 'base_prefix')) {
            $this->tableName = $wpdb->base_prefix . 'userlogins';
        } else {
            $this->tableName = $this->wpdb->prefix . 'userlogins';
        }
    }

    public static function register()
    {
        $instance = new self();

        Utils::checkOptionCallback('cds_track_logins_installed', '1.0', function () use ($instance) {
            $instance->install();
        });

        $instance->addActions();
    }

    public function addActions()
    {
        add_action('wp_login', [$this, 'logUserLogin'], 10, 2);

        add_action('rest_api_init', [$this, 'registerRestRoutes']);

        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function registerRestRoutes()
    {
        register_rest_route('user', '/logins', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getRESTUserLogins'],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);
    }

    public function install()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time_login datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            user_id mediumint(9) NOT NULL,
            user_agent varchar(255) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function uninstall()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS $this->tableName");
    }

    public function insertUserLogin($user, $user_agent): void
    {
        $data = [
            'user_agent' => $user_agent,
            'time_login' => current_time('mysql', 1),
            'user_id'    => $user->ID
        ];

        $this->wpdb->insert($this->tableName, $data);
    }

    public function logUserLogin($user_login, $user): void
    {
        $this->insertUserLogin($user, $user_agent = $this->getUserAgent());
    }

    protected function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getUserLogins(string $current_user_id, int $limit = 3, bool $real_user_agent = false): array
    {
        $real_user_agent = $real_user_agent ? "disable_user_login.user_enabled" : null;
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT time_login, user_agent
                FROM {$this->tableName} 
                WHERE user_id=%d AND user_agent <> %s
                ORDER BY time_login DESC LIMIT {$limit}",
                $current_user_id,
                $real_user_agent
            )
        );
    }

    public function getRESTUserLogins(): WP_REST_Response
    {
        $current_user_id = get_current_user_id();
        $results = $this->getUserLogins($current_user_id, $limit = 3, $real_user_agent = true);

        $parser  = Parser::create();
        $results = array_map(function ($login) use ($parser) {
            $parsed     = $parser->parse($login->user_agent);
            $user_agent = $parsed->os->family . ' | ' . $parsed->ua->family;
            $time_login = new Carbon($login->time_login);

            return [
                'time_login' => $time_login->toIso8601String(),
                'user_agent' => $user_agent,
            ];
        }, $results);

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    public function dashboardWidget(): void
    {
        wp_add_dashboard_widget(
            'cds_login_widget',
            __('Your recent logins', 'cds'),
            [$this, 'trackLoginsPanelHandler'],
        );
    }

    public function trackLoginsPanelHandler(): void
    {
        echo '<div id="logins-panel"></div>';
        $data = 'CDS.renderLoginsPanel();';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}