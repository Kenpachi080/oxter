<?php

namespace App\Http\Controllers;

use App\Models\Eight;
use App\Models\Five;
use App\Models\Fourth;
use App\Models\HelpUser;
use App\Models\Second;
use App\Models\Seven;
use App\Models\Six;
use App\Models\Ten;
use App\Models\TenPopup;
use App\Models\Three;
use App\Models\Twelve;
use App\Models\TwelvePopup;
use App\Models\User;
use App\Models\UserCabinet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Main;

class IndexController extends Controller
{
    /**
     * @var string
     */
    private $url;

    public function __construct()
    {
        $this->url = env('APP_URL', 'http://127.0.0.1:8000');
        $this->url = $this->url . "/storage/";
    }

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

    /**
     * @OA\Get(path="/api/view",
     *   tags={"view"},
     *   operationId="view",
     *   summary="Информация про сайт",
     * @OA\Response(
     *    response=200,
     *    description="Возврощается информация про сайт",
     *   )
     * )
     */
    public function view()
    {
        $return = [];
        $main = Main::first();
        $main->background = $this->url.$main->background;
        $return['one'] = $main;
        $two = Second::first();
        $two->firstimage = $this->url.$two->firstimage;
        $three = Three::first();
        $three->image = $this->url.$three->image;
        $four = Fourth::first();
        $four->image = $this->url.$four->image;
        $five = Five::first();
        $five->image = $this->url.$five->image;
        $six = Six::first();
        $six->images = $this->multiimage(json_decode($six->images));
        $seven = Seven::first();
        $eight = Eight::first();
        $eight->image = $this->url.$eight->image;
        $ten = Ten::first();
        $ten->image = $this->url.$ten->image;
        $eleven = TenPopup::first();
        $eleven->image = $this->url.$eleven->image;
        $twelve = Twelve::first();
        $twelve->midleimage = $this->url.$twelve->midleimage;
        $twelve->footerleftimage = $this->url.$twelve->footerleftimage;
        $twelve->footerrightimage = $this->url.$twelve->footerrightimage;
        $thirteen = TwelvePopup::first();
        $thirteen->image = $this->multiimage(json_decode($thirteen->image));
        $return = [
            'one' => $main,
            'two' => $two,
            'three' => $three,
            'four' => $four,
            'five' => $five,
            'six' => $six,
            'seven' => $seven,
            'eight' => $eight,
            'ten' => $ten,
            'eleven' => $eleven,
            'twelve' => $twelve,
            'thirteen' => $thirteen
        ];
        return response($return, 200);
    }

    private function multiimage($image)
    {
        $return = [];
        if ($image) {
            if (gettype($image) == 'array') {
                foreach ($image as $value) {
                    $return[] = $this->url . $value;
                }
            }
        } else {
            $return = [];
        }
        return $return;
    }
}
