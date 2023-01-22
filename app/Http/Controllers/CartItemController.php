<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CartItemController extends Controller {


    public function products(Request $request) {
        $user = $request->user();
        $cartItems = $user->cartItems();

        if ($request->has('withProduct')) {
            $cartItems->with(['product', 'productAttribute.attributeValues.attribute']);
        }
        return $cartItems->get();
    }

    public function addToCart(Request $request) {
        $user = $request->user();
        $validated = $this->validate($request, [
            'product_id' => 'required|numeric|exists:App\Models\Product,id',
            'quantity' => 'required|numeric',
        ]);

        $quantity = $validated['quantity'];

        if ($request->has('product_attribute_id')) {
            $this->validate($request, [
                'product_attribute_id' => 'required|numeric|exists:App\Models\ProductAttribute,id'
            ]);
            $validated['product_attribute_id'] = $request->get('product_attribute_id');
        }

        $product = Product::find($validated['product_id']);

        if (!$product->status) {
            return response()->json([
                'ok' => false,
                'message' => 'محصول قابل انتخاب نمی باشد'
            ])->setStatusCode(400);
        }
        $productAttribute = ProductAttribute::find($validated['product_attribute_id'] ?? null);
        if ($productAttribute) {
            $productAttributeQuantity = $productAttribute->quantity;
            if ($quantity > $productAttributeQuantity) {
                return response()->json([
                    'ok' => false,
                    'message' => 'تعداد درخواست شده برای این تنوع از محصول بالاتر از تعداد موجودی انبار است.'
                ])->setStatusCode(400);
            }
        } else {
            $productQuantity = $product->quantity;
            if ($quantity > $productQuantity) {
                return response()->json([
                    'ok' => false,
                    'message' => ' تعداد درخواست شده از محصول بالاتر از موجودی فروشگاه است'
                ])->setStatusCode(400);
            }
        }


        if ($productAttribute) {
            $productAttribute->quantity -= $quantity;
            $productAttribute->save();
        } else {
            $product->quantity -= $quantity;
            $product->save();
        }

        return $user->cartItems()->create([
            'quantity' => $quantity,
            'product_id' => $validated['product_id'],
            'product_attribute_id' => $validated['product_attribute_id'] ?? null,
        ]);
    }

    public function deleteCartItem(Request $request, CartItem $cartItem) {
        $user = $request->user();

        if ($cartItem->product_attribute_id) {
            $productAttribute = $cartItem->productAttribute;
            $productAttribute->quantity += $cartItem->quantity;
            $productAttribute->saveOrFail();
        } else {
            $product = $cartItem->product;
            $product->quantity += $cartItem->quantity;
            $product->saveOrFail();
        }

        $user->cartItems()->where('id', $cartItem->id)->delete();

        return $user->cartItems;
    }

    public function updateQuantity(Request $request, CartItem $cartItem) {
        $user = $request->user();

        $validated = $this->validate($request, [
            'quantity' => 'required|numeric'
        ]);

        $cartItemQuantity = $cartItem->quantity;
        $quantity = $validated['quantity'];

        if ($cartItem->product_attribute_id) {
            $productAttributeQuantity = $cartItem->productAttribute->quantity;
            $productAttributeQuantity += $cartItem->quantity;

            if ($quantity > $productAttributeQuantity) {
                return response()->json([
                    'ok' => false,
                    'message' => 'تعداد درخواست شده برای این تنوع از محصول بالاتر از تعداد موجودی انبار است.'
                ])->setStatusCode(400);
            } else {
                $productAttribute = $cartItem->productAttribute;
                $productAttribute->quantity += $cartItem->quantity;
                $productAttribute->quantity -= $quantity;
                $productAttribute->save();
            }
        } else {
            $productQuantity = $cartItem->product->quantity;
            $productQuantity += $cartItem->quantity;
            if ($quantity > $productQuantity) {
                return response()->json([
                    'ok' => false,
                    'message' => ' تعداد درخواست شده از محصول بالاتر از موجودی فروشگاه است'
                ])->setStatusCode(400);
            } else {
                $product = $cartItem->product;
                $product->quantity += $cartItem->quantity;
                $product->quantity -= $quantity;
                $product->save();
            }
        }


        $cartItem->quantity = $quantity;
        $cartItem->saveOrFail();
        return $cartItem;
    }


}
