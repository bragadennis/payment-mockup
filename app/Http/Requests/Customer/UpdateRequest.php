<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest implements IUpdateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    ####
    #   IMPLEMENTING INTERFACES METHODS
    ####

    /**
     * Should return the CPF/CNPJ provided at request for submiting a client.
     *
     * @return string
     */
    public function getCPNumber(): ?string
    {
        return $this->cp_number;
    }

    /**
     * Should return the email address provided at request for submiting a client.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Should return the client's fullname provided at request for submitting a client.
     *
     * @return string
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }
}
