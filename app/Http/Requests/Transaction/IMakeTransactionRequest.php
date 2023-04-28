<?php

namespace App\Http\Requests\Transaction;

use PhpParser\Node\Expr\FuncCall;

interface IMakeTransactionRequest
{
    /**
     * Should return the ID for a customer that is receiving the transference/payment.
     *
     * @return int
     */
    public function getPayeeID(): int;

    /**
     * Should return the amount of value being transfered.
     *
     * @return float
     */
    public function getAmount(): float;
}
