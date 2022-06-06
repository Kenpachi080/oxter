<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user', 'itemid'];

    public function getitem($userid, $itemid)
    {
        $favorite = Favorite::where('itemid', '=', $itemid)->where('user', '=', $userid)->first();
        return $favorite;
    }
}
