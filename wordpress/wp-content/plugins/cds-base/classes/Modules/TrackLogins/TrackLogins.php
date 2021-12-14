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
            __('GC Articles Updates', 'cds'),
            [$this, 'trackLoginsPanelHandler'],
        );
    }

    public function trackLoginsPanelHandler()
    {
        echo '<div id="logins-panel"></div>';
        ob_start();
    ?>

<blockquote class="wp-embedded-content"></blockquote>
<script type='text/javascript'>
<!--//--><![CDATA[//><!--
		/*! This file is auto-generated */
		!function(c,d){"use strict";var e=!1,n=!1;if(d.querySelector)if(c.addEventListener)e=!0;if(c.wp=c.wp||{},!c.wp.receiveEmbedMessage)if(c.wp.receiveEmbedMessage=function(e){var t=e.data;if(t)if(t.secret||t.message||t.value)if(!/[^a-zA-Z0-9]/.test(t.secret)){for(var r,a,i,s=d.querySelectorAll('iframe[data-secret="'+t.secret+'"]'),n=d.querySelectorAll('blockquote[data-secret="'+t.secret+'"]'),o=0;o<n.length;o++)n[o].style.display="none";for(o=0;o<s.length;o++)if(r=s[o],e.source===r.contentWindow){if(r.removeAttribute("style"),"height"===t.message){if(1e3<(i=parseInt(t.value,10)))i=1e3;else if(~~i<200)i=200;r.height=i}if("link"===t.message)if(a=d.createElement("a"),i=d.createElement("a"),a.href=r.getAttribute("src"),i.href=t.value,i.host===a.host)if(d.activeElement===r)c.top.location.href=t.value}}},e)c.addEventListener("message",c.wp.receiveEmbedMessage,!1),d.addEventListener("DOMContentLoaded",t,!1),c.addEventListener("load",t,!1);function t(){if(!n){n=!0;for(var e,t,r=-1!==navigator.appVersion.indexOf("MSIE 10"),a=!!navigator.userAgent.match(/Trident.*rv:11\./),i=d.querySelectorAll("iframe.wp-embedded-content"),s=0;s<i.length;s++){if(!(e=i[s]).getAttribute("data-secret"))t=Math.random().toString(36).substr(2,10),e.src+="#?secret="+t,e.setAttribute("data-secret",t);if(r||a)(t=e.cloneNode(!0)).removeAttribute("security"),e.parentNode.replaceChild(t,e)}}}}(window,document);
//--><!]]>
</script><iframe sandbox="allow-scripts" security="restricted" src="https://articles.cdssandbox.xyz/releases/2-8-0/embed/" width="472" height="300" title="&#8220;2.8.0&#8221; &#8212; GC Articles" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>
    <?php 
        $data = ob_get_contents();
        return $data;
        //ob_end_clean();
        //$data = 'CDS.renderLoginsPanel();';
       // wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
