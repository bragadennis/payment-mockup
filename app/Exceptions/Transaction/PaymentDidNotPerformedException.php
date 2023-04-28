<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;

class PaymentDidNotPerformedException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('O pagamento NÃO foi processado com sucesso. Verifique os dados e tente novamente. Caso o problema persista, por favor, contactar os suporte.');
        }

        $this->setErrorCode('error.payment.not-through');
        $this->setHTTPCode(500);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
