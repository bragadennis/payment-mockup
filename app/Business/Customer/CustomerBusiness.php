<?php

namespace App\Business\Customer;

use App\Business\Customer\Entities\CreateCustomerEntity;
use App\Business\Customer\Entities\RetrieveCustomerEntity;
use App\Business\Customer\Entities\UpdateCustomerEntity;
use App\Business\Customer\Entities\WalletEntity;
use App\Http\Requests\Customer\CreateRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Models\Customer\Customer;
use Illuminate\Http\Request;

class CustomerBusiness
{
    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var CreateCustomerEntity
     */
    private CreateCustomerEntity $createEntity;

    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var UpdateCustomerEntity
     */
    private UpdateCustomerEntity $updateEntity;

    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var RetrieveCustomerEntity
     */
    private RetrieveCustomerEntity $retrieveEntity;

    /**
     * This will hold reference to the create customer entity used
     *      to enforce customer related business rules
     * @var WalletEntity
     */
    private WalletEntity $walletEntity;

    public function __construct(
        CreateCustomerEntity $createEntity,
        UpdateCustomerEntity $updateEntity,
        RetrieveCustomerEntity $retrieveEntity,
        WalletEntity $walletEntity
    )
    {
        $this->createEntity = $createEntity;
        $this->updateEntity = $updateEntity;
        $this->retrieveEntity = $retrieveEntity;
        $this->walletEntity = $walletEntity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $list = $this->retrieveEntity->list();

        return $list->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): Customer
    {
        // Verify if Customer is allowed to be created.
        //    If not, the entity should raise an exception.
        $this->createEntity->isAllowedToCreate( $request );

        // Create Customer
        $customer = $this->createEntity->createCustomer( $request );

        //Create wallet
        $this->walletEntity->create($customer);

        // Return created data
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return Customer
     * @throws CustomerNotFoundException
     */
    public function show(string $id)
    {
        return $this->retrieveEntity->get($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        // Verify if Customer is allowed to be updated
        $this->updateEntity->isAllowedToUpdate($id, $request);

        // update Customer
        $customer = $this->retrieveEntity->get($id);
        $customer = $this->updateEntity->updateCustomer($customer, $request);

        // Return updated data
        return $customer;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete Customer
        $customer = $this->retrieveEntity->get($id);
        $customer = $this->retrieveEntity->delete($customer);

        // Return data
    }
}
