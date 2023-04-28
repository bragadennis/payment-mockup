<?php

namespace App\Business\Customer\Entities;

use App\Exceptions\Customer\WalletNotFoundException;
use App\Exceptions\Transaction\NotEnoughBalanceInWalletException;
use App\Exceptions\Transaction\PaymentDidNotPerformedException;
use App\Models\Customer\Customer;
use App\Models\Customer\Wallet;
use Illuminate\Support\Facades\Log;

class WalletEntity
{
    /**
     * This will create a wallet to a given customer. The first assigned balance value
     *      will always be 0 (zero).
     *
     * @param Customer $customer
     * @return Wallet
     */
    public function create(Customer $customer): Wallet
    {
        return Wallet::create([
            'holder_id' => $customer->id,
            'balance' => 0,
        ]);
    }

    /**
     * This will retrieve a wallet for a given customer.
     *
     * @param Customer $customer
     * @return Wallet
     */
    public function getByCustomer(Customer $customer): Wallet
    {
        try {
            $wallet = Wallet::byHolder($customer)->firstOrFail();
        } catch (\Exception $ex) {
            Log::error("Failed to retrieve wallet for the given customer ID: $customer->id");
            Log::error($ex->getMessage());

            throw new WalletNotFoundException(
                "Não foi possível encontrar a carteira do cliente informado pelo ID: $customer->id. Verifique as informações e tente novamente."
            );
        }

        return $wallet;
    }

    /**
     * This will ensure that the given wallet has enough funds to performe the payment/transaction.
     *
     * @param Wallet $wallet
     * @param float $amount
     * @return bool
     * @throws NotEnoughBalanceInWalletException
     */
    public function hasEnoughToPay(Wallet $wallet, float $amount): bool
    {
        if( $wallet->balance < $amount )
            throw new NotEnoughBalanceInWalletException;

        return true;
    }

    /**
     * This will ensure that the given wallet has enough funds to performe the payment/transaction.
     *
     * @param Wallet $from
     * @param Wallet $to
     * @param float $amount
     * @return bool
     */
    public function payment(Wallet $from, Wallet $to, float $amount): bool
    {
        try {
            $from->balance -= $amount;
            $to->balance -= $amount;

            $from->save();
            $to->save();
        } catch(\Exception $ex) {
            throw new PaymentDidNotPerformedException;
        }

        return true;
    }

    /**
     * This will attempt to delete a received wallet.
     *
     * @param Wallet $wallet
     * @return bool
     */
    public function delete(Wallet $wallet): bool
    {
        try {
            $status = $wallet->deleteOrFail();
        } catch (\Exception $ex) {
            Log::error("Failed to delete wallet for the given ID: $wallet->id");
            Log::error($ex->getMessage());

            throw new WalletNotFoundException;
        }
        return $status;
    }
}
