<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'firstname',
        'lastname',
        'address',
        'country',
        'city',
        'code',
        'phone',
        'delivery',
        'paymenttype',
        'paid',
        "status",
        'total',
        'user',
    ];
}
