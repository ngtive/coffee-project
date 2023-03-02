<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiscountController extends Controller {

    public function index(Request $request) {
        $discounts = Discount::query();
        if ($request->has('search')) {
            $this->validate($request, [
                'search' => 'required|string'
            ]);
            $discounts->whereHas('product', function ($query) use ($request) {
                $query->where('title', $request->get('search'));
            });
        }

        $discounts->with('product');
        return Inertia::render('Admin/discounts/ListDiscount', [
            'discounts' => $discounts->paginate($request->get('per_page') ?? 20)
        ]);
    }

    public function show(Request $request, Discount $discount) {
        $discount->load('product');

        return Inertia::render('Admin/discounts/EditDiscount', [
            'discount' => $discount
        ]);
    }

//    public function store(Request $request)

    public function discountList(Request $request) {

        $discounts = Discount::query();
        if ($request->has('search')) {
            $this->validate($request, [
                'search' => 'required|string'
            ]);
            $discounts->whereHas('product', function ($query) use ($request) {
                $query->where('title', $request->get('search'));
            });
        }

        $discounts->with('product');
        return $discounts->paginate($request->get('per_page') ?? 20);
    }

    public function storeDiscount(Request $request, Product $product) {
        $validated = $this->validate($request, [
            'discount' => 'required|numeric',
            'expire' => 'sometimes|numeric'
        ]);

        $product->discount()->delete();
        $discount = $product->discount()->updateOrCreate([
            'discount' => $validated['discount'],
            'expire_at' => ($request->has('expire') ? now()->addHours((int)$request->get('expire')) : null)
        ]);
        return $discount;
    }

    public function showDiscount(Request $request, Discount $discount) {
        return $discount->load('product');
    }

    public function updateDiscount(Request $request, Discount $discount) {
        $validated = $this->validate($request, [
            'discount' => 'required|numeric',
            'expire' => 'sometimes|numeric'
        ]);

        if ($request->has('expire')) {
            $discount->expire_at = now()->addRealHours($validated['expire']);
        }

        $discount->discount = $validated['discount'];

        $discount->save();
        return $discount->load('product');
    }

    public function deleteDiscount(Request $request, Discount $discount) {
        $discount->delete();
        return ['ok' => true];
    }
}
