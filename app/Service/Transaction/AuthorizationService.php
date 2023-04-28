<?php

namespace App\Service\Transaction;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizationService
{
    /**
     * Service's URL to call for authenticator.
     *
     * @var string
     */
    private string $serviceUrl = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";

    /**
     * This will call an external validator for the payment process.
     *
     * @return Response
     */
    public function call(): Response
    {
        try {
            $response = Http::post($this->serviceUrl);

            if (!$response->ok()) {
                throw new Exception;
            }
        } catch( \Exception $ex) {
            Log::error("Calling for the external authorization service has failed!");
            Log::error($ex->getMessage());

            throw new Exception('Houve um erro ao tentar requisitar o autorizador externo. Por favor tente novamente em alguns instantes.');
        }

        return $response;
    }
}
