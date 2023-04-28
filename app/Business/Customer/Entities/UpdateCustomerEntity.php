<?php

namespace App\Business\Customer\Entities;

use App\Exceptions\Customer\CustomerNotAllowedToUpdateException;
use App\Http\Requests\Customer\IUpdateRequest;
use App\Models\Customer\Customer;

class UpdateCustomerEntity
{
    /**
     * This will ensure that the update business rules were enforced
     *      before submitting the data to the database.
     *
     * @param IUpdateRequest $customer_data
     * @return bool
     * @throws CustomerNotAllowedToUpdateException
     */
    public function isAllowedToUpdate(int $id, IUpdateRequest $customer_data): bool
    {
        if(
            !empty( $customer_data->getCPNumber() ) &&
            Customer::not($id)->byCPNumber($customer_data->getCPNumber())->exists()
        ) {
            throw new CustomerNotAllowedToUpdateException('O número de CPF já existe na base para outro usuário. Só é permitido uma entrada por CPF. Tente um número diferente.');
        }

        if(
            !empty( $customer_data->getEmail() ) &&
            Customer::not($id)->byEmail($customer_data->getEmail())->exists()
        ) {
            throw new CustomerNotAllowedToUpdateException('O endereço de e-mail já existe na base para outro usuário. Só é permitido uma entrada por e-mail. Tente um endereço diferente.');
        }

        return true;
    }

    /**
     * This will update a customer data register at the database.
     *
     * @param IUpdateRequest $data
     * @return Customer
     */
    public function updateCustomer(Customer $customer, IUpdateRequest $data): Customer
    {
        if(!empty( $data->getCPNumber() )) {
            $customer->cp_number = $data->getCPNumber();
        }

        if(!empty( $data->getEmail() )) {
            $customer->email = $data->getEmail();
        }

        if(!empty( $data->getFullname() )) {
            $customer->fullname = $data->getFullname();
        }

        $customer->save();

        return $customer;
    }
}
