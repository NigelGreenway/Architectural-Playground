<?php


namespace Customer\Action;

use Core\ResponseHandler\ResponseHandler;
use Customer\Response\CustomerProfileResponse;
use Demo\Domain\Core\DomainServiceInterface;
use Demo\Domain\Customer\CustomerID;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class description
 *
 * @package Customer\Action
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class ViewProfileAction
{
    private $service,
            $responseHandler
    ;

    public function __construct(
        DomainServiceInterface $service,
        ResponseHandler $responseHandler
    ) {
        $this->service         = $service;
        $this->responseHandler = $responseHandler;
    }

    public function perform(
                $id,
        Request $request
    ) {
        $payload = $this
            ->service
            ->getProfileById(
                CustomerID::fromNative($id)
            );

        return $this
            ->responseHandler
            ->dispatch(
                CustomerProfileResponse::getResponse(),
                $payload,
                $request
            );
    }
}