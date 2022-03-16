<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use WP_REST_Response;
use CDS\Modules\Notify\Utils;
use CDS\Modules\Notify\ListManagerUserProfile;

class NotifyTemplateSender
{
    protected Notices $notices;
    protected string $admin_page = 'cds_notify_send';

    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();

        add_action('admin_menu', [$instance, 'addMenu']);
        add_action('rest_api_init', [$instance, 'registerEndpoints']);

        $listManagerProfile = new ListManagerUserProfile();
        $listManagerProfile->register();
    }

    public static function processListCounts($data): WP_REST_Response
    {
        try {
            $service_id = $data->get_param('service_id');
            $client = new Client([
                'headers' => [
                    "Authorization" => getenv('DEFAULT_LIST_MANAGER_API_KEY')
                ]
            ]);

            $endpoint = getenv('LIST_MANAGER_ENDPOINT');

            $response = $client->request(
                'GET',
                $endpoint . '/lists/' . $service_id . '/subscriber-count?unique=1'
            );

            return new WP_REST_Response(json_decode($response->getBody()->getContents()));
        } catch (Exception $e) {
            return new WP_REST_Response([]);
        }
    }

    public function registerEndpoints(): void
    {
        register_rest_route('wp-notify/v1', '/bulk', [
            'methods' => 'POST',
            'callback' => [$this, 'processSend'],
            'permission_callback' => function () {
                return current_user_can('list_manager_bulk_send');
            }
        ]);

        register_rest_route('wp-notify/v1', '/list_counts/(?P<service_id>[a-zA-Z0-9_-]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'processListCounts'],
            'args' => [
                'service_id' => [],
            ],
            'permission_callback' => function () {
                return current_user_can('list_manager_bulk_send');
            }
        ]);
    }

    public function addMenu(): void
    {
        add_menu_page(
            __('Send Notify Template', "cds-snc"),
            __('Bulk Send', "cds-snc"),
            'list_manager_bulk_send',
            $this->admin_page,
            [$this, 'renderForm'],
            'dashicons-email'
        );
    }

    public function processSend($data): void
    {
        try {
            $sanitized = $this->validate($data);
            $this->send(
                $sanitized['template_id'],
                $sanitized['list_id'],
                $sanitized['list_type'],
                'WP Bulk send',
            );
            wp_redirect($this->baseRedirect() . '&status=200');
            exit();
        } catch (ClientException $e) {
            $this->handleValidationException($e);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        wp_redirect($this->baseRedirect() . '&status=500');
        exit();
    }

    #[ArrayShape([
        'template_id' => "mixed",
        'list_id' => "mixed|string",
        'list_type' => "mixed|string"
    ])] public function validate($data): array
    {
        $template_id = $data['template_id'];

        if (empty($template_id)) {
            wp_redirect($this->baseRedirect() . '&status=400');
            exit();
        }

        $parts = explode('~', $data['list_id']);

        if (!is_array($parts) || count($parts) !== 2) {
            wp_redirect($this->baseRedirect() . '&status=418');
            exit();
        }

        $list_id = $parts[0];
        $list_type = $parts[1];

        return ['template_id' => $template_id, 'list_id' => $list_id, 'list_type' => $list_type];
    }

    public function baseRedirect(): string
    {
        return get_admin_url() . 'admin.php?page=' . $this->admin_page;
    }

    public function send($template_id, $list_id, $template_type, $ref)
    {
        $client = new Client([
            'headers' => [
                "Authorization" => getenv('DEFAULT_LIST_MANAGER_API_KEY')
            ]
        ]);

        $api_key = get_option('NOTIFY_API_KEY');
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

    public function handleValidationException($e)
    {
        $exception = (string)$e->getResponse()->getBody();

        if ($this->isJson($exception)) {
            $exceptions = json_decode($exception);

            $errors = "";

            foreach ($exceptions->detail as $error) {
                $errors = $errors . $error->loc[1] . ': ' . $error->msg . '<br>';
            }

            set_transient('api_response', $errors);

            return;
        }

        set_transient('api_response', __('There has been an error', 'cds-snc'));
        error_log($exception);
    }

    protected function isJson($string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function handleException($e)
    {
        $exception = (string)$e->getResponse()->getBody();

        set_transient('api_response', $exception);
        error_log($e->getMessage(), $e->getCode());
    }

    public function renderForm(): void
    {
        if (isset($_GET['status'])) {
            Notices::handleNotice($_GET['status']);
        }

        $listValues = self::parseJsonOptions(get_option('list_values'));

        FormHelpers::render([
            "list_values" => $listValues
        ]);
    }

    public static function parseJsonOptions($data)
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
}
