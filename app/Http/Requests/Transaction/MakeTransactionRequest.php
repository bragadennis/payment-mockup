<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class MakeTransactionRequest extends FormRequest implements IMakeTransactionRequest
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
    #   INTERFACE METHODS AREA
    ####

    /**
     * Should return the ID for a customer that is receiving the transference/payment.
     *
     * @return int
     */
    public function getPayeeID(): int
    {
        return $this->payee_id;
    }

    /**
     * Should return the amount of value being transfered.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return (float) $this->amount;
    }
}
