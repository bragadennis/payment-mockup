<?php

namespace App\Http\Controllers;

use App\Business\Customer\CustomerBusiness;
use App\Http\Requests\Customer\CreateRequest;
use App\Http\Requests\Customer\UpdateRequest;

class CustomerController extends Controller
{
    /**
     * Business logic class for Customer.
     *
     * @var CustomerBusiness
     */
    private CustomerBusiness $business;

    /**
     * @param CustomerBusiness $business
     */
    public function __construct(CustomerBusiness $business)
    {
        $this->business = $business;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = $this->business->index();

        return response(
            $list,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $customer = $this->business->store($request);

        return response(
            $customer,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $customer_id)
    {
        $customer = $this->business->show($customer_id);

        return response(
            $customer,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $customer_id)
    {
        $customer = $this->business->update($request, $customer_id);

        return response(
            $customer,
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $customer_id)
    {
        $this->business->destroy($customer_id);

        return response(
            204
        );
    }
}
