<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerNotAllowedToUpdateException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('Não foi permitida a atualização do usuário. Favor, verificar os dados submetidos.');
        }

        $this->setErrorCode('error.customer.update.not-allowed');
        $this->setHTTPCode(422);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
