<?php


namespace Customer\Action;

use Core\ResponseHandler\ResponseHandler;
use Customer\Response\CustomerListingResponse;
use Demo\Domain\Core\DomainServiceInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class description
 *
 * @package Customer\Action
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerListingAction
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
        Request $request
    ) {
        $payload = $this
            ->service
            ->getCustomerListing()
        ;

        return $this
            ->responseHandler
            ->dispatch(
                CustomerListingResponse::getResponse(),
                $payload,
                $request
            );
    }
}