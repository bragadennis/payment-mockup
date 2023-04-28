<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;

class ExternalAuthorizerNotClearedException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('O autorizador externo não aprovou a operação. Aguarde alguns minutos e tente novamente mais tarde.');
        }

        $this->setErrorCode('error.payment.external-authorizer.not-cleared');
        $this->setHTTPCode(422);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
