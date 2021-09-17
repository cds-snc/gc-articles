<?php

namespace CDS\Modules\TrackLogins;

use Carbon\Carbon;
use UAParser\Parser;
use WP_REST_Response;

class TrackLogins
{
    protected $tableName;
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb      = $wpdb;
        $this->tableName = $this->wpdb->prefix.'userlogins';

        add_action('wp_login', [$this, 'logUserLogin'], 10, 2);

        add_action('rest_api_init', function () {
            $this->registerRestRoutes();
        });

        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function registerRestRoutes()
    {
        register_rest_route('user', '/logins', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getUserLogins'],
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

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function uninstall()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS $this->tableName");
    }

    public function logUserLogin($user_login, $user)
    {
        $data = [
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'time_login' => current_time('mysql', 1),
            'user_id'    => $user->id
        ];

        $this->wpdb->insert($this->tableName, $data);
    }

    public function getUserLogins(): WP_REST_Response
    {
        $current_user_id = get_current_user_id();
        $parser          = Parser::create();

        $results = $this->wpdb->get_results(
            $this->wpdb->prepare("
                SELECT time_login, user_agent 
                FROM {$this->tableName} 
                WHERE user_id=%d 
                ORDER BY time_login DESC LIMIT 3", $current_user_id)
        );

        $results = array_map(function ($login) use ($parser) {
            $parsed     = $parser->parse($login->user_agent);
            $user_agent = $parsed->os->family.' | '.$parsed->ua->family;
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
        wp_add_inline_script('cds-snc-admin-js', $data, 'after' );
    }
}
