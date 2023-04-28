<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerNotAllowedToMakePaymentException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('O cliente/usuário informado não é permitido realizar pagamentos. Selecione um usuário que não seja do tipo LOGISTA/SELLER e tente novamente');
        }

        $this->setErrorCode('error.transaction.customer.not-allowed');
        $this->setHTTPCode(403);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
