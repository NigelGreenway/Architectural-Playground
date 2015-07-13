<?php


namespace Customer\Action;

use Core\Response\Notification\SuccessResponse;
use Core\Response\Notification\WarningResponse;
use Core\ResponseHandler\ResponseHandler;
use Demo\Domain\Core\DomainServiceInterface;
use Demo\Domain\Core\EmailAddress;
use Demo\Domain\Customer\CustomerID;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class description
 *
 * @package Customer\Action
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class UpdateEmailAddressAction
{
    private $service,
            $responseHandler
    ;

    public function __construct(
        DomainServiceInterface $service,
        ResponseHandler        $responseHandler
    ) {
        $this->service         = $service;
        $this->responseHandler = $responseHandler;
    }

    public function perform(
                $id,
        Request $request
    ) {
        $requestPayload = json_decode($request->getContent(), true);

        try {
            $payload = $this
                ->service
                ->updateEmailAddress(
                    CustomerID::fromNative($id),
                    EmailAddress::fromNative($requestPayload['emailAddress'])
                )
            ;

            return $this
                ->responseHandler
                ->dispatch(
                    SuccessResponse::getResponse(),
                    $payload,
                    $request
                );
        } catch(\Exception $exception) {

            return $this
                ->responseHandler
                ->dispatch(
                    WarningResponse::getResponse(),
                    [
                        'message' => $exception->getMessage(),
                    ],
                    $request
                );
        }

    }
}