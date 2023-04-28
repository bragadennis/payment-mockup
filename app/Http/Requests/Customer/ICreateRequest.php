<?php

namespace App\Http\Requests\Customer;

interface ICreateRequest
{
    /**
     * Should return the CPF/CNPJ provided at request for submitting a client.
     *
     * @return string
     */
    public function getCPNumber(): string;

    /**
     * Should return the email address provided at request for submitting a client.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Should return the client's fullname provided at request for submitting a client.
     *
     * @return string
     */
    public function getFullname(): string;

    /**
     * Should return the type enum for a submited client.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Should return the raw password submitted at a client's registration.
     *
     * @return string
     */
    public function getPassword(): string;
}
