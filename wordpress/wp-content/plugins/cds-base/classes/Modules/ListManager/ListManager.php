<?php

declare(strict_types=1);

namespace CDS\Modules\ListManager;

class ListManager
{
    protected string $listManagerAdminScreenName = 'bulk-send_page_lists';

    public function __construct()
    {
        //
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue($hook_suffix)
    {
        if ($hook_suffix == $this->listManagerAdminScreenName) {
            try {
                $path  = plugin_dir_path(__FILE__) . 'app/build/asset-manifest.json';
                $json  = file_get_contents($path);
                $data  = json_decode($json, true);
                $files = $data['files'];

                wp_enqueue_style('list-manager', $files['main.css'], null, '1.0.0');

                wp_enqueue_script(
                    'list-manager',
                    $files['main.js'],
                    null,
                    '1.0.0',
                    true,
                );

                wp_localize_script('list-manager', 'CDS_LIST_MANAGER', [
                    'endpoint' => esc_url_raw(getenv('LIST_MANAGER_ENDPOINT')),
                ]);
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }
}
