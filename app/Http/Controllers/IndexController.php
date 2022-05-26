<?php

namespace App\Http\Controllers;

use App\Models\HelpUser;
use App\Models\User;
use App\Models\UserCabinet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * @OA\Get(path="/api/cabinet",
     *   tags={"auth"},
     *   operationId="viewreturn",
     *   summary="Информация про сайт",
     * @OA\Response(
     *    response=200,
     *    description="Возврощается информация про сайт",
     *   )
     * )
     */
    public function cabinet()
    {
        $help = UserCabinet::first();
        $help->help = HelpUser::all();
        return response($help, 200);
    }
}
