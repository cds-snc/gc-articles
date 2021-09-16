<?php

declare(strict_types=1);

use GuzzleHttp\Client;

require_once __DIR__ . '/NotifySettings.php';
require_once __DIR__ . '/Notices.php';
require_once __DIR__ . '/FormHelpers.php';

add_action('admin_menu', ['NotifyTemplateSender', 'add_menu']);

add_action('rest_api_init', ['NotifyTemplateSender', 'register_endpoints']);

class NotifyTemplateSender
{
    public static string $admin_page = 'cds_notify_send';


    public function __construct()
    {

    }

    public static function base_redirect(): string
    {
        return get_admin_url() . 'admin.php?page=' . self::$admin_page;
    }

    public static function find_api_key($service_id): string
    {
        $service_ids = self::parse_service_ids_from_env();
        $api_key = "";
        foreach ($service_ids as $key => $value) {
            if (trim($service_id) == trim($key)) {
                $api_key = $value;
            }
        }
        return $api_key;
    }

    public static function validate($data): array
    {
        $template_id = $data['template_id'];
        $api_key = self::find_api_key($data['service_id']);

        if (empty($template_id)) {
            wp_redirect(self::base_redirect() . '&status=400');
            exit();
        }

        $parts = explode('~', $data['list_id']);

        if (!is_array($parts) || count($parts) !== 2) {
            wp_redirect(self::base_redirect() . '&status=418');
            exit();
        }

        $list_id = $parts[0];
        $list_type = $parts[1];

        return ['api_key' => $api_key, 'template_id' => $template_id, 'list_id' => $list_id, 'list_type' => $list_type];

    }

    public static function process_send($data): void
    {

        try {
            $sanitized = self::validate($data);

            self::send(
                $sanitized['api_key'],
                $sanitized['template_id'],
                $sanitized['list_id'],
                $sanitized['list_type'],
                'WP Bulk send',
            );

            wp_redirect(self::base_redirect() . '&status=200');
            exit();
        } catch (Exception $e) {
            if ($e->hasResponse()) {
                self::handle_response($e);
            } else {
                error_log($e->getMessage(), 503);
            }

            wp_redirect(self::base_redirect() . '&status=500');
            exit();
        }
    }

    public static function send($api_key, $template_id, $list_id, $template_type, $ref)
    {
        $client = new Client([
            'headers' => [
                "Authorization" => getenv('LIST_MANAGER_API_KEY')
            ]
        ]);

        $endpoint = getenv('LIST_MANAGER_ENDPOINT');

        return $client->request('POST', $endpoint . '/send', [
            'json' => [
                'service_api_key' => $api_key,
                'template_id' => $template_id,
                'list_id' => $list_id,
                'template_type' => $template_type,
                'job_name' => $ref,
            ],
        ]);
    }

    public static function handle_response($e)
    {
        $exception = (string)$e->getResponse()->getBody();
        $exceptions = json_decode($exception);

        $errors = "";
        foreach ($exceptions->detail as $error) {
            $errors = $errors . $error->loc[1] . ': ' . $error->msg . '<br>';
        }

        set_transient('api_response', $errors);
        error_log($exception, $e->getCode());

    }

    public static function parse_service_ids_from_env()
    {
        $str = getenv('LIST_MANAGER_NOTIFY_SERVICES');
        $arr = explode(',', $str);
        $service_ids = [];

        for ($i = 0; $i < count($arr); $i++) {
            $key_value = explode('~', $arr [$i]);
            $service_ids[$key_value [0]] = $key_value [1];
        }

        return $service_ids;
    }

    public static function parse_json_options($data)
    {
        if (empty($data)) {
            return [];
        }

        $data = preg_replace(
            '/[ \t]+/',
            ' ',
            preg_replace('/[\r\n]+/', "\n", $data),
        );

        return json_decode($data, true);
    }

    public static function process_list_counts()
    {
        try {
            $client = new Client([
                'headers' => [
                    "Authorization" => getenv('LIST_MANAGER_API_KEY')
                ]
            ]);

            $endpoint = getenv('LIST_MANAGER_ENDPOINT');

            $service_id = getenv('LIST_MANAGER_SERVICE_ID');

            $response = $client->request('GET',
                $endpoint . '/lists/' . $service_id . '/subscriber-count');

            return new WP_REST_Response(json_decode($response->getBody()->getContents()));
        } catch (Exception $e) {
            return new WP_REST_Response([]);
        }

    }

    public static function register_endpoints(): void
    {
        register_rest_route('wp-notify/v1', '/bulk', [
            'methods' => 'POST',
            'callback' => [self::class, 'process_send'],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);

        register_rest_route('wp-notify/v1', '/list_counts', [
            'methods' => 'GET',
            'callback' => [self::class, 'process_list_counts'],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);
    }

    // RENDER
    public static function add_menu(): void
    {
        add_menu_page(
            __('Send Notify Template', "cds-snc"),
            __('Notify', "cds-snc"),
            'level_0',
            self::$admin_page,
            ['NotifyTemplateSender', 'render_form'],
            'dashicons-email'
        );

        NotifySettings::add_menu();
    }

    public static function render_form(): void
    {
        if (isset($_GET['status'])) {
            Notices::handle_notice($_GET['status']);
        }

        FormHelpers::render([
            "service_ids" => self::parse_service_ids_from_env(),
            "list_values" => self::parse_json_options(get_option('list_values'))
        ]);

    }
}
