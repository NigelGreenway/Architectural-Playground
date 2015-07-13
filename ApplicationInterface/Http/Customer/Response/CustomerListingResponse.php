<?php


namespace Customer\Response;

use Core\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class description
 *
 * @package Customer\Response
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerListingResponse implements ResponseInterface
{
    public static function getResponse()
    {
        return new static;
    }

    public function availableAcceptHeaders()
    {
        return [
            'html',
        ];
    }

    public function getStatusCode()
    {
        return Response::HTTP_OK;
    }

    public function getHtml()
    {
        return '@Customer\customer_listing.html.twig';
    }

    public function getJson()
    {}

    public function getXml()
    {}
}