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


    public static function validate($data): array
    {
        $template_id = $data['template_id'];

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

        return ['template_id' => $template_id, 'list_id' => $list_id, 'list_type' => $list_type];

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

    public static function process_send($data): void
    {

        try {
            $sanitized = self::validate($data);

            self::send(
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

    public static function send($template_id, $list_id, $template_type, $ref)
    {
        $client = new Client([
            'headers' => [
                "Authorization" => getenv('LIST_MANAGER_API_KEY')
            ]
        ]);

        $endpoint = getenv('LIST_MANAGER_ENDPOINT');

        return $client->request('POST', $endpoint . '/send', [
            'json' => [
                'template_id' => $template_id,
                'list_id' => $list_id,
                'template_type' => $template_type,
                'job_name' => $ref,
            ],
        ]);
    }

    public static function parse_json_options($data)
    {
        $data = preg_replace(
            '/[ \t]+/',
            ' ',
            preg_replace('/[\r\n]+/', "\n", $data),
        );

        return json_decode($data, true);

        if (empty($data)) {
            return [];
        }
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
                $endpoint . '/lists/'.$service_id.'/subscriber-count');

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

        FormHelpers::render(self::parse_json_options(get_option('list_values')));

    }
}
