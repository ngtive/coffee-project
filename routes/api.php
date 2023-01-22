<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('recaptcha', function (Request $request) {
    $request->validate([
        'gcode' => 'string',
    ]);
    $result = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('GOOGLE_RECAPTCHA_SECRET', null),
        'response' => $request->get('gcode')
    ]);
    if ($result->json()['success']) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false])->setStatusCode(400);
    }
});

Route::get('provinces', [\App\Http\Controllers\ProvinceController::class, 'index']);
Route::get('provinces/{province}/cities', [\App\Http\Controllers\ProvinceController::class, 'show']);

Route::resource('products', \App\Http\Controllers\ProductController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::get('categories/{category}/products', [\App\Http\Controllers\CategoryController::class, 'products']);


Route::get('brands', [\App\Http\Controllers\BrandController::class, 'index']);
Route::get('brands/{brand}/products', [\App\Http\Controllers\BrandController::class, 'products']);


Route::prefix('auth')->middleware(['cors'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('login', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'authenticateWithEmail']);
    });
    Route::prefix('user')->group(function () {
        Route::post('login', [\App\Http\Controllers\UserController::class, 'userLogin']);
        Route::post('verify-login', [\App\Http\Controllers\UserController::class, 'verifyLogin']);
    });
});


Route::middleware(['auth:admin-api'])->prefix('admin')->group(function () {

    /* Auth */
    Route::get('/', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'currentUser']);
    Route::get('logout', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'logout']);

    /* Products */
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::post('products/{product}/brand', [\App\Http\Controllers\ProductController::class, 'addBrand']);
    Route::delete('products/{product}/brand', [\App\Http\Controllers\ProductController::class, 'deleteBrand']);

    Route::get('attributes', [\App\Http\Controllers\ProductAttributeController::class, 'indexAttribute']);
    Route::post('attributes', [\App\Http\Controllers\ProductAttributeController::class, 'storeAttribute']);
    Route::get('attributes/{attribute}', [\App\Http\Controllers\ProductAttributeController::class, 'showAttribute']);
    Route::post('attributes/{attribute}/values', [\App\Http\Controllers\ProductAttributeController::class, 'storeAttributeValue']);


    /* Store, update, delete ProductAttribute */
    Route::post('products/{product}/product_attribute', [\App\Http\Controllers\ProductAttributeController::class, 'storeProductAttribute']);
    Route::delete('productAttributes/{productAttribute}', [\App\Http\Controllers\ProductAttributeController::class, 'destroy']);
    Route::patch('productAttributes/{productAttribute}', [\App\Http\Controllers\ProductAttributeController::class, 'update']);

    /* Categories */
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    /* Attach categories to product */
    Route::post('products/{product}/categories', [\App\Http\Controllers\CategoryController::class, 'attachToProduct']);


    /* Brands */
    Route::resource('brands', \App\Http\Controllers\BrandController::class);


    /* Orders */
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index']);
    Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::post('orders/sent', [\App\Http\Controllers\OrderController::class, 'sent']);
    Route::post('orders/print', [\App\Http\Controllers\OrderController::class, 'print']);
    Route::get('orders/user/{user}', [\App\Http\Controllers\OrderController::class, 'userOrders']);
//    Route::get('orders/user/{user}/print')

    /* Users */
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::patch('users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('user.update');


    /* Addresses */
    Route::get('addresses', [\App\Http\Controllers\AddressController::class, 'addressList']);
    Route::get('users/{user}/addresses', [\App\Http\Controllers\AddressController::class, 'showUserAddresses']);


});


Route::middleware('auth:api')->prefix('user')->group(function () {
    // params: withAddresses, withCartItems, withOrders
    Route::get('', [\App\Http\Controllers\UserController::class, 'showMe']);

    Route::patch('', [\App\Http\Controllers\UserController::class, 'updateProfile']);


    /* Addresses */
    Route::resource('addresses', \App\Http\Controllers\AddressController::class);

    // Get the latest address
    Route::get('addresses/latest', [\App\Http\Controllers\AddressController::class, 'latest']);

    /* CartItems */
    Route::get('cart-items', [\App\Http\Controllers\CartItemController::class, 'products']);
    Route::post('cart-items', [\App\Http\Controllers\CartItemController::class, 'addToCart']);
    Route::delete('cart-items/{cartItem}', [\App\Http\Controllers\CartItemController::class, 'deleteCartItem']);
    Route::patch('cart-items/{cartItem}', [\App\Http\Controllers\CartItemController::class, 'updateQuantity']);

    /* Orders */

    // params: status, withItems
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'ordersList']);
    Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'order']);
    Route::post('orders', [\App\Http\Controllers\OrderController::class, 'makeOrderFromCartItem']);

    /* Payments */

});

