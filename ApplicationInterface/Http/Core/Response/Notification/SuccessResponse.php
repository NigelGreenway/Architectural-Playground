<?php


namespace Core\Response\Notification;

use Core\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class description
 *
 * @package Core\Response\Notification
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class SuccessResponse implements ResponseInterface
{
    public static function getResponse()
    {
        return new static;
    }

    public function availableAcceptHeaders()
    {
        return [
            'html',
            'json',
            'xml',
        ];
    }

    public function getStatusCode()
    {
        return Response::HTTP_OK;
    }

    public function getHtml()
    {
        return 'html';
    }

    public function getJson()
    {
        return '@Base\Notification\success.json.twig';
    }

    public function getXml()
    {
        // TODO: Implement getXml() method.
    }

}