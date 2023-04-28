<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;

class MessageNotSentException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('A mensagem ao recebedor do pagamento não foi enviado com sucesso dado a uma instabilidade no serviço externo de envios. Ela não será tentada novamente.');
        }

        $this->setErrorCode('error.payment.not-through');
        $this->setHTTPCode(500);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
