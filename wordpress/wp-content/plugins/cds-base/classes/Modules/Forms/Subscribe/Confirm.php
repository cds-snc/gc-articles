<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use WP_REST_Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Confirm
{
    protected string $redirect = '';

    public function confirm(WP_REST_Request $request)
    {
        $subscriptionId = $request['id'];

        $client = new Client();

        $endpoint = getenv('LIST_MANAGER_ENDPOINT');

        try {
            $client->request('GET', $endpoint . '/subscription/' . $subscriptionId . '/confirm', [
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
            error_log($e->getMessage());
            wp_redirect('/list-manager-error');
            exit();
        } catch (ServerException $e) {
            error_log($e->getMessage());
            wp_redirect('/list-manager-error');
            exit();
        }

        // If a redirect was returned from the endpoint, handle it
        if ($this->redirect) {
            wp_redirect($this->redirect);
            exit();
        }

        // Default redirect
        wp_redirect('/confirmation');
    }
}
