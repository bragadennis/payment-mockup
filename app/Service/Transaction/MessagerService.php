<?php

namespace App\Service\Transaction;

use App\Models\Customer\Customer;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessagerService
{
    #TODO: Include resilience feature through queues.

    /**
     * Service's URL to call for authenticator.
     *
     * @var string
     */
    private string $serviceUrl = "http://o4d9z.mocklab.io/notify";

    /**
     * This will call an external validator for the payment process.
     *
     * @return Response
     */
    public function call(Customer $customer): Response
    {
        try {
            $response = Http::post(
                $this->serviceUrl,
                ['email' => $customer->email]
            );

            if (!$response->ok()) {
                throw new Exception;
            }
        } catch( \Exception $ex) {
            Log::error("Calling for the external messager service has failed!");
            Log::error($ex->getMessage());

            throw new Exception('Houve um erro ao tentar requisitar mensageria. A mensagem não será enviada.');
        }

        return $response;
    }
}
