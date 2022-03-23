<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use WP_REST_Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Unsubscribe
{
    protected string $redirect;

    public function unsubscribe(WP_REST_Request $request)
    {
        $subscriptionId = $request['id'];

        $client = new Client([
            'headers' => [
                "Authorization" => getenv('DEFAULT_LIST_MANAGER_API_KEY')
            ]
        ]);

        $endpoint = getenv('LIST_MANAGER_ENDPOINT');

        try {
            $client->request('GET', $endpoint . '/unsubscribe/' . $subscriptionId, [
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
        } catch (ClientException $e) {
            // @TODO: handle error responses (including 404 not found)
            // @TODO: unsubscribe seems to always return 500 error?
            error_log($e->getMessage());
        }

        if ($this->redirect) {
            wp_redirect($this->redirect);
            exit();
        }

        // @TODO: What do we do if no redirect set?
        error_log($request['id']);
    }
}
