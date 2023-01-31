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

Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Auth\Admin\LoginController::class, 'adminHome']);
});

Route::get('/', function () {
    return response()->json(['ok' => false, 'message' => 'صفحه مورد نظر پیدا نشد'])->setStatusCode(404);
});
