<?php

namespace App\Http\Controllers;


use App\Models\Address;
use App\Models\Order;
use App\Models\Province;
use Carbon\Carbon;
use GhaniniaIR\Shipping\Drivers\PishtazDriver;
use GhaniniaIR\Shipping\Models\City;
use GhaniniaIR\Shipping\Models\State;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class OrderController extends Controller {


    public function index(Request $request) {
        $order = Order::query();

        if ($request->has('orderBy')) {
            $orderBy = $request->get('orderBy');
        }

        if ($request->has('status')) {
            $status = $request->get('status');
            switch ($status) {
                case 'pending':
                    $order->whereNull('printed_at')
                        ->whereNull('sending_at');
                    break;
                case 'printed':
                    $order->whereNotNull('printed_at')
                        ->whereNull('sending_at');
                    break;
                case 'sent':
                    $order->whereNotNull('sending_at');
                    break;
                default:
                    break;

            }
        }

        return $order->paginate(20);
    }

    public function show(Request $request, Order $order) {
        return $order;
    }

    public function sent(Request $request) {
        $validated = $this->validate($request, [
            'orders.*' => 'required|numeric|exists:App\Models\Order,id'
        ]);

        Order::where('id', $request->get('orders'))->updateOrFail([
            'sending_at' => now()
        ]);
        return ['ok' => true];

    }

    public function print(Request $request) {
        $validated = $this->validate($request, [
            'orders.*' => 'required|numeric|exists:App\Models\Order,id',
        ]);
        Order::where('id', $request->get('orders'))->updateOrFail([
            'printed_at' => now()
        ]);
        return ['ok' => true];
    }

    public function userOrders(Request $request) {
        $validated = $this->validate($request, [
            'user_id' => 'required|numeric|exists:App\Models\User,id',
        ]);

        $user = \App\Models\User::findOrFail($validated['user_id']);
        return $user->orders;
    }

    public function ordersList(Request $request) {
        $orders = $request->user()->orders();
        if ($request->has('status')) {
            switch ($request->get('status')) {
                case 'pending':
                    $orders->whereNull('printed_at')
                        ->whereNull('sending_at');
                    break;
                case 'sent':
                    $orders->whereNotNull('sending_at');
                    break;
                case 'paid':
                    $orders->whereNotNull('paid_at');
                    break;
                case 'printed':
                    $orders->whereNotNull('printed_at');
                    break;
            }
        }
        return $orders->get();
    }

    public function order(Request $request, Order $order) {
        $user_id = $request->user()->id;

        if ($order->user_id != $user_id) {
            return response()->json([
                'ok' => false,
                'message' => 'شما دسترسی ندارید'
            ])->setStatusCode(403);
        }

        if ($request->has('withItems')) {
            $order->load('items');
        }

        return $order;
    }


//    public function storeOrder(Request $request) {
//        $user = $request->user();
//        $cartItems = $user->cartItems;
//
//    }

    public function paymentCallback(Request $request) {
        if (!$request->has('order_id')) {
            return;
        }

        $order = Order::findOrFail($request->get('order_id'));
        $user = $request->user();

        if ($order->user_id != $user->id) {
            return response()->json([
                'ok' => false,
                'message' => 'شما دسترسی به این فاکتور ندارید',
            ])->setStatusCode(403);
        }

        $order->paid_at = now();
        $order->save();

        return $order;

    }

    public function makeOrderFromCartItem(Request $request) {
        $user = $request->user();
        $validated = $this->validate($request, [
            'address_id' => 'required|numeric|exists:App\Models\Address,id',
            'description' => 'sometimes|string',
        ]);
        $address = Address::where('user_id', $user->id)->where('id', $validated['address_id'])
            ->firstOrFail();


        $order = new Order();
        $order->address_id = $address->id;
        $order->user_id = $user->id;

        if ($request->has('description')) {
            $order->description = $validated['description'];
        }

        $cartItems = $user->cartItems;
        if (count($cartItems) == 0) {
            return response()->json([
                'ok' => false,
                'message' => 'سبد خرید خالی است'
            ])->setStatusCode(400);
        }


        $total_price = 0;
        $total_weight = 0;

        $total_discount = 0;
        $total_paid = 0;

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if ($cartItem->productAttribute) {
                $total_price += ($cartItem->productAttribute->amount * $cartItem->quantity);
                $total_weight += ($cartItem->productAttribute->weight * $cartItem->quantity);
            } else {
                $total_price += ($cartItem->product->amount * $cartItem->quantity);
                $total_weight += ($cartItem->product->weight * $cartItem->quantity);
            }

        }

        $order->total_price = $total_price;
        $order->total_price = $total_paid;

        $sourceProvince = Province::where('name', 'تهران')
            ->firstOrFail();
        $sourceCity = $sourceProvince->cities()->where('name', 'تهران')->firstOrFail();


        $courier = (new PishtazDriver())
            ->weight($total_weight)
            ->cost($total_price)
            ->source(State::find($sourceProvince->shipping_id), City::find($sourceCity->shipping_id))
            ->destination(State::find($address->province->shipping_id), City::find($address->city->shipping_id))
            ->calculate();

        $order->courier_price = $courier;
        $order->total_paid = ($total_price + $courier);
        $order->discounts = 0;
        $order->tax = 0;
        $order->save();


        foreach ($cartItems as $cartItem) {
            if ($cartItem->productAttribute) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'product_attribute_id' => $cartItem->product_attribute_id,
                    'quantity' => $cartItem->quantity,
                    'amount' => $cartItem->productAttribute->amount,
                    'total' => $cartItem->productAttribute->amount,
                    'discount' => 0,
                ]);
            } else {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'amount' => $cartItem->product->amount,
                    'total' => $cartItem->product->amount,
                    'discount' => 0,
                ]);
            }
        }

        return response()->json([
            'order' => $order->load('items'),
            'token' => '1234|' . $order->id,
            'ok' => true,
        ]);
    }

    public function statistics(Request $request) {
        $nowShamsi = Jalalian::now();
        $todayOrders = Order::query()->whereBetween('created_at', [
            Carbon::now()->startOfDay(),
            now(),
        ])->whereNotNull('paid_at')->count();

        $thisMonthOrders = Order::query()->whereBetween(
            'created_at',
            [
                (new Jalalian($nowShamsi->getYear(), $nowShamsi->getMonth(), $nowShamsi->getDay()))->toCarbon()->startOfDay(),
                now()
            ],
        )->whereNotNull('paid_at')->count();
        $thisWeekOrders = Order::query()->whereBetween('created_at', [
            Carbon::now()->startOfWeek(Carbon::SATURDAY),
            now(),
        ])->whereNotNull('paid_at')->count();


        return response()->json([
            'today' => $todayOrders,
            'month' => $thisMonthOrders,
            'week' => $thisWeekOrders,
        ]);
    }
}
