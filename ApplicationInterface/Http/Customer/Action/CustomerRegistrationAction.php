<?php


namespace Customer\Action;

use Core\ActionUtility;
use Core\ResponseHandler\ResponseHandler;
use Customer\Response\CustomerRegistrationResponse;
use Demo\Domain\Core\DomainServiceInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class description
 *
 * @package Customer\Action
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerRegistrationAction
{
    private $service,
            $responseHandler,
            $utility
    ;

    public function __construct(
        DomainServiceInterface $service,
        ActionUtility          $actionUtility,
        ResponseHandler        $responseHandler
    ) {
        $this->service         = $service;
        $this->responseHandler = $responseHandler;
        $this->utility         = $actionUtility;
    }

    public function perform(
        Request $request
    ) {
        switch($request->getMethod()) {

            case 'GET':
                return $this
                    ->responseHandler
                    ->dispatch(
                        CustomerRegistrationResponse::getResponse(),
                        [],
                        $request
                    );
                break;

            case 'POST':
                $payload = $this
                    ->service
                    ->register($request->request);

                return $this
                    ->utility
                    ->redirect(
                        $this
                            ->utility
                            ->generateRoute(
                                'customer_profile',
                                [
                                    'id' => $payload['customerID']
                                ]
                            )
                    );
                break;

        }
    }
}