<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'holder_id',
        'balance',
    ];

    ####
    #   SCOPE METHODS AREA
    ####

    /**
     * @param Customer $customer
     */
    public function scopeByHolder($query, Customer $customer)
    {
        return $query->where('holder_id', $customer->id);
    }
}
