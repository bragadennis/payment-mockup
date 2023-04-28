<?php

namespace App\Business\Customer\Entities;

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Models\Customer\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class RetrieveCustomerEntity
{
    /**
     * This will list all data for the customers using a Laravel's default pagination.
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return Customer::paginate();
    }

    /**
     * This will retrieve a single register customer identified by $id property.
     *
     * @param int $id
     * @return Customer
     */
    public function get(int $id): Customer
    {
        try {
            $customer = Customer::findOrFail($id);
        } catch (\Exception $ex) {
            Log::error("Failed to retrieve Customer for the given ID: $id");
            Log::error($ex->getMessage());

            throw new CustomerNotFoundException;
        }

        return $customer;
    }

    /**
     * This will attempt to delete a customers register. It will return a
     *      boolean to inform if it succeeded.
     *
     * @param int $id
     * @return bool
     */
    public function delete(Customer $customer): bool
    {
        try {
            $status = $customer->deleteOrFail();
        } catch (\Exception $ex) {
            Log::error("Failed to delete Customer for the given ID: $customer->id");
            Log::error($ex->getMessage());

            throw new CustomerNotFoundException;
        }
        return $status;
    }
}
