<?php


namespace Core\Response;


/**
 * Class description
 *
 * @package Core\ResponseHandler
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
interface ResponseInterface
{
    public static function getResponse();
    public function availableAcceptHeaders();
    public function getStatusCode();
    public function getHtml();
    public function getJson();
    public function getXml();
}