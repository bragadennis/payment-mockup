<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class WalletNotFoundException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('Não foi possível recuperar a carteira. Favor, confirmar se o usuário realmente tem essa carteira e tente novamente.');
        }

        $this->setErrorCode('error.wallet.retrieve.not-found');
        $this->setHTTPCode(404);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
