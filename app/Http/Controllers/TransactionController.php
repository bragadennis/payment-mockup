<?php

namespace App\Http\Controllers;

use App\Business\Transaction\TransactionBusiness;
use App\Http\Requests\Transaction\MakeTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Business logic class for Customer.
     *
     * @var TransactionBusiness
     */
    private TransactionBusiness $business;

    /**
     * @param TransactionBusiness $business
     */
    public function __construct(TransactionBusiness $business)
    {
        $this->business = $business;
    }

    /**
     * This will call for a transaction to be generated
     */
    public function makeTransaction(MakeTransactionRequest $request, int $payer_id)
    {
        $this->business->makeTransaction($request, $payer_id);
    }
}
