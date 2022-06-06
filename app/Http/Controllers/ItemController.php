<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\MostColor;
use App\Models\MostSize;
use App\Models\Size;
use App\Models\SizeGuide;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->url = env('APP_URL', 'http://127.0.0.1:8000');
        $this->url = $this->url . "/storage/";
    }

    /**
     * @OA\Get(path="/api/item/guide",
     *   tags={"item"},
     *   operationId="guide",
     *   summary="Гайд размеров",
     * @OA\Response(
     *    response=200,
     *    description="Гайд размеров",
     *   )
     * )
     */
    public function guide()
    {
        return response(SizeGuide::first(), 200);
    }
    /**
     * @OA\Post(
     * path="/api/items/",
     * summary="Товары",
     * description="Товары",
     * operationId="items",
     * tags={"item"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={""},
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *  ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="CallBack",
     *        )
     *     )
     * )
     */
    public function items(Request $request)
    {
        $item = $this->getitem(1, 0, $request->api_token);
        return response($item, 200);
    }

    public function singleview(Request $request, $id)
    {
        $item = $this->getitem(2, $id, $request->api_token);
        if (count($item) < 1) {
            return response(['message'  => 'Товар не найден'], 404);
        }
        return response($item, 200);
    }



    private function getitem($type = 1, $id = 0, $api_token = 0)
    {
        $block = Item::orderBy('price', 'desc');
        $favoriteItems = $this->favorite($api_token);
        if ($type == 1) {
            $item = $block->get();
        } else {
            $item = Item::where('id', '=', $id)->get();
        }
        foreach ($item as $block) {
            $size = MostSize::where('item_id', '=', $block->id)->select('id', 'size_id as size')->get();
            $block->size = $size;
            $color = MostColor::where('item_id', '=', $block->id)->select('id', 'color_id as color')->get();
            $block->color = $color;
            $block->image = $this->url . $block->image;
            $block->images = $this->multiimage(json_decode($block->images));
            if (isset($favoriteItems) && $favoriteItems != []) {
                if (array_search($block->id, $favoriteItems)) {
                    $block->isFavorite = 1;
                } else {
                    $block->isFavorite = 0;
                }
            } else {
                $block->isFavorite = 0;
            }
        }
        return $item;
    }

    private function favorite($token)
    {
        $favoriteItems = [];
        if ($token) {
            $user = User::where('api_token', '=', $token)->first();
            if ($user) {
                Auth::login($user);
                $favorite = Favorite::where('user', '=', Auth::id())->get();
                if (count($favorite) > 0) {
                    foreach ($favorite as $key) {
                        $favoriteItems[$key->ItemID] = $key->ItemID;
                    }
                }
            }
        }
        return $favoriteItems;
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
