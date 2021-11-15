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
                $endpoint . '/lists/' . $service_id . '/subscriber-count'
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
                return current_user_can('delete_posts');
            }
        ]);

        register_rest_route('wp-notify/v1', '/list_counts/(?P<service_id>[a-zA-Z0-9_-]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'processListCounts'],
            'args' => [
                'service_id' => [],
            ],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);
    }

    public function addMenu(): void
    {
        add_menu_page(
            __('Send Notify Template', "cds-snc"),
            __('Bulk Send', "cds-snc"),
            'level_0',
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
                $sanitized['api_key'],
                $sanitized['template_id'],
                $sanitized['list_id'],
                $sanitized['list_type'],
                'WP Bulk send',
            );
            $serviceId = Utils::deserializeServiceIds($sanitized['api_key']);
            wp_redirect($this->baseRedirect() . '&status=200&serviceId=' . $serviceId);
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
        'api_key' => "string",
        'template_id' => "mixed",
        'list_id' => "mixed|string",
        'list_type' => "mixed|string"
    ])] public function validate($data): array
    {
        $template_id = $data['template_id'];
        $api_key = $this->findApiKey($data['service_id']);

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

        return ['api_key' => $api_key, 'template_id' => $template_id, 'list_id' => $list_id, 'list_type' => $list_type];
    }

    public function findApiKey($service_id): string
    {
        $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
        $service_ids = Utils::deserializeServiceIds($serviceIdData);
        $api_key = "";
        foreach ($service_ids as $key => $value) {
            if (trim($service_id) == trim($value['service_id'])) {
                $api_key = $value['api_key'];
            }
        }

        return $api_key;
    }

    public function baseRedirect(): string
    {
        return get_admin_url() . 'admin.php?page=' . $this->admin_page;
    }

    public function send($api_key, $template_id, $list_id, $template_type, $ref)
    {
        $client = new Client([
            'headers' => [
                "Authorization" => getenv('DEFAULT_LIST_MANAGER_API_KEY')
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

        set_transient('api_response', _('There has been an error', 'cds-snc'));
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

        $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');

        $listValues = [];
        $serviceIds = [];

        try {
            $serviceIds = Utils::deserializeServiceIds($serviceIdData);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        try {
            $listValues = self::parseJsonOptions(get_option('list_values'));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        FormHelpers::render([
            "service_ids" => $serviceIds,
            "list_values" => $listValues
        ]);
    }

    public static function parseJsonOptions($data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No list data');
        }

        $data = preg_replace(
            '/[ \t]+/',
            ' ',
            preg_replace('/[\r\n]+/', "\n", $data),
        );

        return json_decode($data, true);
    }
}
