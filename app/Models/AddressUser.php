<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressUser extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['userID', 'phone', 'city', 'country', 'province', 'address', 'postcode'];
}
