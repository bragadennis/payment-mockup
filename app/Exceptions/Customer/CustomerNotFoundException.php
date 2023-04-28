<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerNotFoundException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('Não foi possível recuperar o usuário. Favor, confirmar se o usuário realmente existe e tentar novamente.');
        }

        $this->setErrorCode('error.customer.retrieve.not-found');
        $this->setHTTPCode(404);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
