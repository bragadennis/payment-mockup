<?php

namespace App\Business\Transaction;

use App\Business\Customer\Entities\RetrieveCustomerEntity;
use App\Business\Customer\Entities\WalletEntity;
use App\Business\Transaction\Entities\TransactionEntity;
use App\Enums\Transactions\Reason;
use App\Enums\Users\Type;
use App\Exceptions\Customer\CustomerNotAllowedToMakePaymentException;
use App\Exceptions\Customer\CustomerNotFoundException;
use App\Exceptions\Customer\WalletNotFoundException;
use App\Exceptions\Transaction\ExternalAuthorizerNotClearedException;
use App\Exceptions\Transaction\NotEnoughBalanceInWalletException;
use App\Http\Requests\Transaction\IMakeTransactionRequest;
use App\Models\Customer\Customer;
use App\Models\Customer\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionBusiness
{
    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var RetrieveCustomerEntity
     */
    private RetrieveCustomerEntity $retrieveCustomerEntity;

    /**
     * This will hold reference to the wallet entity used to enforce
     *      business rules related to wallet model (balance, ownership, etc.)
     * @var WalletEntity
     */
    private WalletEntity $walletEntity;

    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var TransactionEntity
     */
    private TransactionEntity $transactionEntity;

    public function __construct(
        RetrieveCustomerEntity $retrieveCustomerEntity,
        WalletEntity $walletEntity,
        TransactionEntity $transactionEntity
    )
    {
        $this->retrieveCustomerEntity = $retrieveCustomerEntity;
        $this->walletEntity = $walletEntity;
        $this->transactionEntity = $transactionEntity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Transaction
     */
    public function makeTransaction(IMakeTransactionRequest $data, int $payer_id): Transaction
    {
        $payer = $this->getPayer($payer_id);
        $payee = $this->getPayee($data->getPayeeID());

        $transaction = $this->transactionEntity->create(
            $payer,
            $payee,
            $data->getAmount()
        );

        try {
            $this->payerIsAllowedToMakePayment($payer);
            $this->payerHasEnoughBalance($payer, $data->getAmount());

            $transaction = $this->attemptPayment($transaction, $payer, $payee, $data->getAmount());
        } catch(CustomerNotAllowedToMakePaymentException $ex) {
            $this->transactionEntity->deny(
                $transaction,
                Reason::UNAUTHORIZED_PAYER_TYPE,
                "O usuário tentando realizar a operação é um vendedor e não tem permissão para realizar a transação."
            );
        } catch(NotEnoughBalanceInWalletException $ex) {
            $this->transactionEntity->deny(
                $transaction,
                Reason::INSUFICIENT_FUNDS,
                "O usuário tentando realizar a operação não tem saldo suficiente."
            );
        } catch(ExternalAuthorizerNotClearedException $ex) {
            $this->transactionEntity->deny(
                $transaction,
                Reason::EXTERNAL_AUTHORIZATION_REFUSED,
                "O autorizador externo não liberou a transação. Operação não concluída."
            );
        } catch(\Exception $ex) {
            Log::error("There has been an unhandled error at the payment attempt.");
            Log::error($ex->getMessage());

            $this->transactionEntity->deny(
                $transaction,
                Reason::UNHANDLED_ERROR,
                "Ocorreu um erro inesperado com a transação de pagamento. Esse incidente será reportado!"
            );
        }

        $this->transactionEntity->fulfill(
            $transaction,
            "O pagamento foi concluído com sucesso."
        );

        return $transaction;
    }

    ####
    #   PRIVATE METHODS AREA
    ####

    /**
     * This will try to retrieve a Payer Customer's register.
     *
     * @param int $payer_id
     * @return Transaction
     * @throws ExternalAuthorizerNotClearedException
     * @throws PaymentDidNotPerformedException
     */
    private function attemptPayment(Transaction $transaction, Customer $payer, Customer $payee, float $amount): Transaction
    {
        // Consult external authorizer
        $this->transactionEntity->externalAuthorizer();

        try{
            DB::beginTransaction();

            // Change transaction to perform payment.
            $payer_wallet = $this->walletEntity->getByCustomer($payer);
            $payee_wallet = $this->walletEntity->getByCustomer($payee);

            $this->walletEntity->payment($payer_wallet, $payee_wallet, $amount);
        } catch (\Exception $ex) {
            DB::rollBack();

            throw $ex;
        }

        DB::commit();

        // Send message to payee
        $this->transactionEntity->messagePayee($payee);

        return $transaction;
    }

    /**
     * This will try to retrieve a Payer Customer's register.
     *
     * @param int $payer_id
     * @return Customer
     * @throws CustomerNotFoundException
     */
    private function getPayer(int $payer_id): Customer
    {
        try {
            $payer = $this->retrieveCustomerEntity->get($payer_id);
        } catch (\Exception $ex) {
            Log::error("An error has occured in the retrieving of a payer customer by the identifier of: $payer_id");
            Log::error("Exception message: " . $ex->getMessage());

            throw new CustomerNotFoundException("O usuário pagador não pode ser encontrado com base no identificador (ID) fornecido. Verifique as informações e tente novamente.");
        }

        return $payer;
    }

    /**
     * This will evaluate wether the current customer is allowed to make payments to other users.
     *
     * @param Customer $payer
     * @return bool
     * @throws CustomerNotAllowedToMakePaymentException
     */
    private function payerIsAllowedToMakePayment(Customer $payer): bool
    {
        if ($payer->type == Type::SELLER ) {
            throw new CustomerNotAllowedToMakePaymentException;
        }

        return true;
    }

    /**
     * This will evaluate wether the current customer has enough balance in his wallet to
     *      perform the payment/exchange.
     *
     * @param Customer $payer
     * @return bool
     * @throws WalletNotFoundException
     * @throws NotEnoughBalanceInWalletException
     */
    private function payerHasEnoughBalance(Customer $payer, float $amount): bool
    {
        $wallet = $this->walletEntity->getByCustomer($payer);

        $this->walletEntity->hasEnoughToPay($wallet, $amount);

        return true;
    }

    /**
     * This will try to retrieve a Payer Customer's register.
     *
     * @param int $payer_id
     * @return Customer
     * @throws CustomerNotFoundException
     */
    private function getPayee(int $payee_id): Customer
    {
        try {
            $payer = $this->retrieveCustomerEntity->get($payee_id);
        } catch (\Exception $ex) {
            Log::error("An error has occured in the retrieving of a payee customer by the identifier of: $payee_id");
            Log::error("Exception message: " . $ex->getMessage());

            throw new CustomerNotFoundException("O usuário recebedor não pode ser encontrado com base no identificador (ID) fornecido. Verifique as informações e tente novamente.");
        }

        return $payer;
    }
}
