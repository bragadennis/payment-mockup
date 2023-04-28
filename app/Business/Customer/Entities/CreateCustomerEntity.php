<?php

namespace App\Business\Customer\Entities;

use App\Enums\Users\Type;
use App\Exceptions\Customer\CustomerNotAllowedToCreateException;
use App\Http\Requests\Customer\ICreateRequest;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Hash;
use ValueError;

class CreateCustomerEntity
{
    /**
     * This will ensure that the creation business rules were enforced
     *      before submitting the data to the database.
     *
     * @param ICreateRequest $customer_data
     * @return bool
     */
    public function isAllowedToCreate(ICreateRequest $customer_data): bool
    {
        if( Customer::byCPNumber($customer_data->getCPNumber())->exists() )
            throw new CustomerNotAllowedToCreateException('O número de CPF já existe na base. Só é permitido uma entrada por CPF. Tente um número diferente.');

        if( Customer::byEmail($customer_data->getEmail())->exists() )
            throw new CustomerNotAllowedToCreateException('O endereço de e-mail já existe na base. Só é permitido uma entrada por e-mail. Tente um endereço diferente.');

        return true;
    }

    /**
     * This will create a customer register at the database.
     *
     * @param ICreateRequest $data
     * @return Customer
     */
    public function createCustomer(ICreateRequest $data): Customer
    {
        $customer = Customer::create([
            'fullname'  => $data->getFullname(),
            'email'     => $data->getEmail(),
            'cp_number' => $data->getCPNumber(),
            'type'      => $this->formatType( $data->getType() ),
            'password'  => $this->hashPassword( $data->getPassword() ),
        ]);

        return $customer;
    }

    ####
    #   PRIVATE METHODS AREA
    ####

    /**
     * This will validade that the passed value for the type field is a valid one.
     *
     * @param string $type
     * @return Type
     */
    private function formatType(string $type): Type
    {
        try {
            $type = Type::from($type);
        } catch (ValueError $ex) {
            throw new CustomerNotAllowedToCreateException('O Tipo de usuário enviado não é válido. Apenas os tipos "customer" e "seller" são aceitos. Revise os dados enviados e tente novamente.');

        }

        return $type;
    }

    /**
     * This will hash the password so it will be stored in a secured manner.
     *
     * @param string $pass
     * @return string
     */
    private function hashPassword(string $pass): string
    {
        return Hash::make($pass);
    }
}
