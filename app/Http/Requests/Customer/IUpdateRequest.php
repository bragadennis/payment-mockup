<?php

namespace App\Http\Requests\Customer;

interface IUpdateRequest
{
    /**
     * Should return the CPF/CNPJ provided at request for submitting a client.
     *
     * @return string
     */
    public function getCPNumber(): ?string;

    /**
     * Should return the email address provided at request for submitting a client.
     *
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * Should return the client's fullname provided at request for submitting a client.
     *
     * @return string
     */
    public function getFullname(): ?string;
}
