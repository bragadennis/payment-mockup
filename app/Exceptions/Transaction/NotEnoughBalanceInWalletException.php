<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;

class NotEnoughBalanceInWalletException extends BaseException
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        // Fallback message in case the exception is launched without any explanation for what it is the cause.
        if( empty($this->getErrorMessage()) ) {
            $this->setErrorMessage('Não há saldo suficiente na carteira para realizar a transação. Favor, adicione saldo na carteira e tente novamente.');
        }

        $this->setErrorCode('error.wallet.balance.not-enough-funds');
        $this->setHTTPCode(422);


        return response(
            $this->getPayload(),
            $this->getHttpCode()
        );
    }
}
