<?php

namespace App\Business\Transaction\Entities;

use App\Enums\Transactions\Reason;
use App\Enums\Transactions\Status;
use App\Exceptions\Transaction\ExternalAuthorizerNotClearedException;
use App\Exceptions\Transaction\MessageNotSentException;
use App\Models\Customer\Customer;
use App\Models\Customer\Transaction;
use App\Service\Transaction\AuthorizationService;
use App\Service\Transaction\MessagerService;

class TransactionEntity
{
    /**
     * Service that call the external authorization API.
     * @var AuthorizationService
     */
    private AuthorizationService $authorizer;

    private MessagerService $messager;

    public function __construct(
        AuthorizationService $authorizer,
        MessagerService $messager
    )
    {
        $this->authorizer = $authorizer;
        $this->messager = $messager;
    }

    /**
     * This will generate a model transaction with all the data needed to
     *      inform who are the players in question and the total amount.
     *      In case the transaction got fulfilled or denied, it will be changed in
     *      a latter operation. Worst case scenario, this will remain has a log the
     *      attempted transaction.
     *
     * @param Customer $payer
     * @param Customer $payee
     * @param float $amount
     * @return Transaction
     */
    public function create(Customer $payer, Customer $payee, float $amount): Transaction
    {
        return Transaction::create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => $amount,
            'status' => Status::ATTEMPED,
            'message' => "Transação iniciada. Necessário aguardar conclusão.",
        ]);
    }

    /**
     * This will update the transaction to a denied one.
     *
     * @param Transaction $transaction
     * @param string $reason
     * @param string|null $message
     * @return Transaction
     */
    public function deny(Transaction $transaction, Reason $reason, string $message = null): Transaction
    {
        $transaction->status = Status::REJECTED;
        $transaction->reason = $reason;
        $transaction->message = $message ?? '';

        $transaction->save();

        return $transaction;
    }

    /**
     * This will update the transaction to a fulfilled one. This indicates that
     *      the operation was performed under the expected restritions and that the
     *      funds were transfered from one account to the other.
     *
     * @param Transaction $transaction
     * @param string|null $message
     * @return Transaction
     */
    public function fulfill(Transaction $transaction, string $message = null): Transaction
    {
        $transaction->status = Status::APPROVED;
        $transaction->message = $message ?? '';

        $transaction->save();

        return $transaction;
    }

    /**
     * Consult external authorizer for the clearense to the operation.
     *
     * @return bool
     */
    public function externalAuthorizer(): bool
    {
        // Call external service to authorize the payment.
        $response = $this->authorizer->call();
        $body = json_decode($response->json());

        // Identify if the payment was authorized.
        if ($body['message'] !== "Autorizado") {
            throw new ExternalAuthorizerNotClearedException;
        }

        return true;
    }

    /**
     * This will attempt to inform the payee that funds were transfered to his wallet.
     *
     * @param Customer $payee
     * @return [type]
     */
    public function messagePayee(Customer $payee): bool
    {
        // Call external service to authorize the payment.
        $response = $this->messager->call($payee);
        $body = json_decode($response->json());

        // Identify if the payment was authorized.
        if ($body['message'] !== "Success") {
            throw new MessageNotSentException;
        }

        return true;
    }
}
