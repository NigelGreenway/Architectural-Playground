<?php

namespace Core\ResponseHandler;

use Core\Response\ResponseInterface;
use Demo\Infrastructure\Adapter\TwigAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class description
 *
 * @package Core\ResponseHandler
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class ResponseHandler
{
    private $template;

    public function __construct(
        TwigAdapter $twigAdapter
    ) {
        $this->template = $twigAdapter;
    }

    private function render(
        $template,
        array $payload    = [],
        $statusCode = Response::HTTP_OK,
        array $headers    = []
    ) {
        return new Response(
            $this
                ->template
                ->render(
                    $template,
                    $payload
                ),
            $statusCode,
            $headers
        );
    }

    public function dispatch(
        ResponseInterface $response,
        array             $payload,
        Request           $request
    ) {
        $acceptedHeaders = $response->availableAcceptHeaders();

        switch ($request->headers->get('Accept')) {
            case 'application/json':
                if (in_array('json', $acceptedHeaders) === true) {
                    return $this
                        ->render(
                            $response->getJson(),
                            [
                                'payload' => $payload,
                            ],
                            $response->getStatusCode(),
                            [
                                'Content-Type' => 'application/json',
                            ]
                        );
                }
                break;

            // Todo: build XML builder
            case 'application/xml':
                if (in_array('xml', $acceptedHeaders) === true) {
                    return $this
                        ->render(
                            $response->getXml(),
                            [
                                'payload' => $payload,
                            ],
                            $response->getStatusCode(),
                            [
                                'Content-Type' => 'application/xml',
                            ]
                        );
                }
                break;

            default:
                return $this
                    ->render(
                        $response->getHtml(),
                        $payload,
                        $response->getStatusCode()
                    );
                break;
        }

    }
}