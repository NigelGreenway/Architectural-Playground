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
final class WarningResponse implements ResponseInterface
{
    public static function getResponse()
    {
        return new static;
    }

    public function availableAcceptHeaders()
    {
        return [
            'json',
        ];
    }

    public function getStatusCode()
    {
        return Response::HTTP_FORBIDDEN;
    }

    public function getHtml()
    {
    }

    public function getJson()
    {
        return '@Base\Notification\warning.json.twig';
    }

    public function getXml()
    {
    }

}