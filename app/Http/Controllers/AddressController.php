<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAddressRequest;
use App\Models\AddressUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/auth/address/view",
     * summary="Посмотреть адреса",
     * description="Посмотреть адреса",
     * operationId="addressview",
     * tags={"address"},
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
     *    description="CallBack с адресами",
     *        )
     *     )
     * )
     */
    public function view(Request $request)
    {
        $address = AddressUser::where('userID', '=', Auth::id())->get();
        return response($address, 200);
    }

    /**
     * @OA\Post(
     * path="/api/auth/address/view/{id}",
     * summary="Посмотреть отедельный адресс",
     * description="Посмотреть отедельный адресс",
     * operationId="addressviewsingle",
     * tags={"address"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"api_token, id"},
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *       @OA\Property(property="id", type="string", format="string", example="1"),
     *  ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="CallBack с адресами",
     *        )
     *     )
     * )
     */
    public function singleview(Request $request)
    {
        $address = AddressUser::where('id', '=', $request->id)->first();
        if ($address->userID != Auth::id()) {
            return response(['message' => 'Доступ запрещен'], 403);
        }
        return response($address, 200);
    }

    /**
     * @OA\Post(
     * path="/api/auth/address/delete",
     * summary="Удалить адресс",
     * description="Удалить адресс",
     * operationId="addressdelete",
     * tags={"address"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"api_token, id"},
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *       @OA\Property(property="id", type="string", format="string", example="1"),
     *  ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="CallBack с адресами",
     *        )
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $address = AddressUser::where('id', '=', $request->id)->first();
        if ($address->userID != Auth::id()) {
            return response(['message' => 'Доступ запрещен'], 403);
        }
        $delete = $address->delete();
        if ($delete) {
            return response(['message' => 'Адрес был успешно удален'], 200);
        } else {
            return response(['messagew' => 'Произошла ошибка'], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/auth/address/add",
     * summary="Создать адресс",
     * description="Создать адресс",
     * operationId="addressadd",
     * tags={"address"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Апи Токен",
     *    @OA\JsonContent(
     *       required={"phone, city, country, province, address, postcode, api_token"},
     *       @OA\Property(property="phone", type="string", format="string", example="123123123"),
     *       @OA\Property(property="city", type="string", format="string", example="almata"),
     *       @OA\Property(property="country", type="string", format="string", example="almata"),
     *       @OA\Property(property="province", type="string", format="string", example="almata"),
     *       @OA\Property(property="address", type="string", format="string", example="kazakhstan"),
     *       @OA\Property(property="postcode", type="string", format="string", example="123"),
     *       @OA\Property(property="api_token", type="string", format="string", example="R6efxg145osgPcJpb2LTXUXy1rcKezAAuPGYvdH5fFogUqT3xAGk06An6qCW"),
     *  ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="CallBack с адресами",
     *        )
     *     )
     * )
     */
    public function add(AddAddressRequest $request)
    {
        $userid = Auth::id();
        $address = AddressUser::create([
            'phone' => $request->phone,
            'city' => $request->city,
            'country' => $request->country,
            'province' => $request->province,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'userID' => $userid,
        ]);
        if (!$address) {
            return response(['message' => 'Не удалось создать адрес'], 409);
        }
        $response = [
            'message' => 'Адресс был успешно создан',
            'address' => $address
        ];
        return response($response, 201);
    }
}
