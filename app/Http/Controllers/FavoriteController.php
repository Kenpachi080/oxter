<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteRequest;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/favorite/add",
     * summary="Добавить в избранное",
     * description="Добавить в избранное",
     * operationId="addfavorite",
     * tags={"favorite"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"api_token, itemid"},
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *       @OA\Property(property="itemid", type="string", format="string", example="1"),
     *  ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="CallBack",
     *        )
     *     )
     * )
     */
    public function add(FavoriteRequest $request)
    {
        $favorite = Favorite::where('itemid', '=', $request->itemid)->where('user', '=', Auth::id())->first();
        if ($favorite) {
            return response(['message' => 'Товар создан'], 203);
        }
        $favoriteCreate = Favorite::create(['user' => Auth::id(), 'itemid' => $request->itemid]);
        if (!$favoriteCreate) {
            return response(['message' => 'Не получилось создать товар']);
        }
        return response($favoriteCreate, 201);
    }
    /**
     * @OA\Post(
     * path="/api/favorite/view",
     * summary="Посмотреть избранные товары",
     * description="Посмотреть избранные товары",
     * operationId="viewfavorite",
     * tags={"favorite"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"api_token"},
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
    public function view()
    {
        $favoite = Favorite::leftjoin('items', 'items.id', '=', 'favorites.itemid')
            ->select('items.*')
            ->get();
        foreach ($favoite as $item) {
            $item->isFavorite = 1;
        }
        return response($favoite, 200);
    }
    /**
     * @OA\Post(
     * path="/api/favorite/delete",
     * summary="Удалить избранный товар",
     * description="Удалить избранный товар",
     * operationId="deletefavorite",
     * tags={"favorite"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"api_token, itemid"},
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *       @OA\Property(property="itemid", type="string", format="string", example="1"),
     *  ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="CallBack",
     *        )
     *     )
     * )
     */
    public function delete(FavoriteRequest $request)
    {
        $favorite = Favorite::getitem(Auth::id(), $request->itemid);
        if ($favorite) {
            $favorite->delete();
            return response(['message' => 'Товар удален'], 200);
        } else {
            return response(['message' => 'Товар не был найден'], 404);
        }
    }
}
