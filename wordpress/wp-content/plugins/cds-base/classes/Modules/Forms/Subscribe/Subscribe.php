<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use CDS\Modules\Forms\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Subscribe
{
    protected function isJson($string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function handleException($e): string
    {
        $exception = (string)$e->getResponse()->getBody();

        error_log($exception);

        if ($this->isJson($exception)) {
            try {
                $exceptions = json_decode($exception);

                $errors = "";

                if (!$exceptions || !property_exists($exceptions, "detail")) {
                    throw new \Exception("details not found");
                }

                foreach ($exceptions->detail as $error) {
                    $errors = $errors . $error->loc[1] . ': ' . $error->msg . '<br>';
                }

                return $errors;
            } catch (\Exception $e) {
                return __("Internal server error", "cds-snc");
            }
        }

        return __("Internal server error", "cds-snc");
    }

    protected function subscribe(string $email, string $listId): array
    {
        try {
            $client = new Client([
                'headers' => [
                    "Authorization" => getenv('DEFAULT_LIST_MANAGER_API_KEY')
                ]
            ]);

            $endpoint = getenv('LIST_MANAGER_ENDPOINT');

            $client->request('POST', $endpoint . '/subscription', [
                'json' => [
                    "email" => $email,
                    "list_id" => $listId,
                    "service_api_key" => get_option('NOTIFY_API_KEY')
                ],
                'allow_redirects' => [
                    'on_redirect' => function (
                        RequestInterface $request,
                        ResponseInterface $response,
                        UriInterface $uri
                    ) {
                        $this->redirect = strval($uri);
                    }
                ]
            ]);

            return ["success" => __("Thanks for subscribing", "cds-snc"), "redirect" => $this->redirect];
        } catch (ClientException $exception) {
            $error = $this->handleException($exception);
            return ["error" => $error];
        } catch (RequestException  $exception) {
            return ["error" => __("Internal server error", "cds-snc")];
        }
    }

    public function confirmSubscription(): array
    {
        $nonceErrorMessage = Utils::isNonceErrorMessage($_POST);
        if ($nonceErrorMessage) {
            return ['error' => true, "error_message" => $nonceErrorMessage];
        }

        if (!isset($_POST["email"]) || $_POST["email"] === "") {
            return [
                'error' =>  true,
                'error_message' => __(
                    'Please complete the required field(s) to continue',
                    'cds-snc',
                ),
                'keys' => ['email']
            ];
        }

        if (!isset($_POST["list_id"]) || $_POST["list_id"] === "") {
            return [
                'error' =>  true,
                'error_message' => __('Unknown subscription', 'cds-snc'),
                'keys' => ['list_id']
            ];
        }

        $email = sanitize_email($_POST["email"]);
        $listId = sanitize_text_field($_POST['list_id']);

        return $this->subscribe($email, $listId);
    }
}
