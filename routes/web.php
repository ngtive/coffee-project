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

Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'index'])->name('login');
    Route::post('admin/login', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'authenticateWithEmail']);
});


Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'adminHome'])->name('admin.home');

    Route::post('logout', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'logout'])->name('logout');
    Route::delete('categories/{category}/cover', [\App\Http\Controllers\CategoryController::class, 'deleteCover'])->name('categories.delete-cover');
    Route::get('products/store', [\App\Http\Controllers\ProductController::class, 'showStoreForm'])->name('products.show-store-form');
    Route::post('attributes/{attribute}/values', [\App\Http\Controllers\ProductAttributeController::class, 'storeAttributeValue'])->name('attribute.store.values');

    Route::resource('brands', \App\Http\Controllers\BrandController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('discounts', \App\Http\Controllers\DiscountController::class);
    Route::resource('attributes', \App\Http\Controllers\ProductAttributeController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
});
