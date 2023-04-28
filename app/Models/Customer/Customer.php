<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "users";

    protected $fillable = [
        'fullname',
        'email',
        'cp_number',
        'type',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    ####
    #   SCOPE METHODS AREA
    ####

    /**
     * @param string $cp_number
     */
    public function scopeByCPNumber($query, string $cp_number)
    {
        $query->where('cp_number', $cp_number);
    }

    /**
     * @param string $email
     */
    public function scopeByEmail($query, string $email)
    {
        $query->where('email', $email);
    }
    /**
     * @param string $email
     */
    public function scopeNot($query, int $id)
    {
        $query->where('id', '!=', $id);
    }
}
