<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use const http\Client\Curl\AUTH_ANY;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->url = env('APP_URL', 'http://127.0.0.1:8000');
        $this->url = $this->url . "/storage/";
    }

    public function create(OrderRequest $request)
    {
        $endsum = 0;
        $create = [
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'code' => $request->code,
            'phone' => $request->phone,
            'delivery' => $request->delivery,
            'paymenttype' => $request->paymenttype,
            'paid' => 0,
            'status' => 1,
            'user' => Auth::id(),
        ];
        $order = Order::create($create);
        $items = [];
        foreach ($request->items as $block) {
            $item = Item::where('id', '=', $block['id'])->first();
            if (!$item) {
                continue;
            }
            $endsum = $endsum + $item->price * $block['count'];
            $orderItem = OrderItem::create([
                'item' => $item->id,
                'name' => $item->name,
                'color' => $block['color'],
                'size' => $block['size'],
                'orderid' => $order->id,
                'price' => $item->price * $block['count'],
                'count' => $block['count'],
            ]);
            array_push($items, $orderItem);
        }
        if (!$items) {
            return response(['message' => 'Товары не существуют'], 404);
        }
//        $endsum = $endsum + $priceDelivery->price;
        $order->total = $endsum;
        $order->save();
        $order = Order::where('id', '=', $order->id)->first();
        $response = [];
        $response['Order'] = [];
        $response['Items'] = [];
        array_push($response['Order'], $order);
        array_push($response['Items'], $items);
        return response($response, 201);
    }

    public function view(Request $request)
    {
        $order = Order::where('user', '=', Auth::id())->get();
        foreach ($order as $block) {
            $item = OrderItem::where('orderid', '=', $block->id)->get();
            $block->items = $item;
        }

        return response($order, 200);
    }

    public function singleview(Request $request, $id)
    {
        $order = Order::where('id', '=', $id)->first();
        if (!$order) {
            return response(['message' => 'Нету заказы'], 403);
        }
        if ($order->user != Auth::id()) {
            return response(['message' => "Доступ запрещен"], 403);
        }
        $item = OrderItem::where('orderid', '=', $order->id)->get();
        $order->items = $item;

        return response($order, 200);
    }
}
