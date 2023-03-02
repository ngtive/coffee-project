<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin/login', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'index'])
    ->name('login');
Route::post('admin/login', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'authenticateWithEmail']);
Route::post('logout', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'logout'])
    ->name('logout');


Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'adminHome'])->name('admin.home');

    Route::resource('brands', \App\Http\Controllers\BrandController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::get('products/store', [\App\Http\Controllers\ProductController::class, 'showStoreForm'])
        ->name('products.show-store-form');
    Route::resource('discounts', \App\Http\Controllers\DiscountController::class);
});

/*Route::get('/', function () {
    return response()->json(['ok' => false, 'message' => 'صفحه مورد نظر پیدا نشد'])->setStatusCode(404);
});*/
