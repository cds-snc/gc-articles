<?php

declare(strict_types=1);

namespace CDS\Modules\DBInsights;

use wpdb;

class DBInsights
{
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_action('rest_api_init', [$this, 'registerRestRoutes']);

        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function registerRestRoutes(): void
    {

        // http://localhost/wp-json/gc-articles/v1/cleanup
        register_rest_route('maintenance/v1', '/deleted-site-tables', [
            'methods' => 'GET',
            'callback' => [$this, 'cleanupDbTables'],
            'permission_callback' => function () {
                return current_user_can('administrator');
            }
        ]);
    }

    public function parseBlogId($str): ?string
    {
        try {
            $parts = explode("_", $str);

            // only parse if this is a WordPress table
            if ($this->db->prefix !== $parts[0] . "_") {
                return null;
            }

            return intval($parts[1]) >= 1 ? $parts[1] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getBlogIds(): array
    {
        try {
            // get blogIds
            $sites = get_sites();
            return array_map(fn($site) => $site->blog_id, $sites);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTables(): array
    {
        $sql = "SHOW TABLES LIKE '%'";
        $results = $this->db->get_results($sql);

        $tables = [];
        foreach ($results as $value) {
            foreach ($value as $tableName) {
                array_push($tables, $tableName);
            }
        }

        return $tables;
    }

    public function cleanupDbTables()
    {
        try {
            $blogIds = $this->getBlogIds();
            $tables = $this->getTables();

            $deleted = [];
            foreach ($tables as $table) {
                $id = $this->parseBlogId($table);
                if (!$id) {
                    continue;
                }

                if (!in_array($id, $blogIds)) {
                    array_push($deleted, ["name" => $table]);
                }
            }

            $payload = new \stdClass();
            $payload->blogIds = $blogIds;
            $payload->tables = $deleted;

            return $payload;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function dashboardWidget(): void
    {
        if (!is_super_admin()) {
            return;
        }

        wp_add_dashboard_widget(
            'cds_db_widget',
            __('Database Insights', 'cds') . "<span class='alpha'>" . __("Alpha", "cds-snc") . "</span>",
            [$this, 'dbInsightsPanelHandler'],
        );
    }

    public function dbInsightsPanelHandler(): void
    {
        echo '<div id="db-insignts-panel"></div>';
        $data = 'CDS.renderDBInsightsPanel();';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
