<?php

use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\Public;
use App\Http\Controllers\Api\V1\User;
use App\Http\Controllers\Api\V1\Rider;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::apiResource('categories', Public\CategoryController::class)->only(['index', 'show']);
Route::apiResource('meals', Public\MealController::class)->only(['index', 'show']);


Route::post('register', Auth\RegisterController::class);
Route::post('login', Auth\LoginController::class);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('password-reset', Auth\PasswordResetController::class);
    Route::post('logout', Auth\LogoutController::class);


    Route::prefix('super-admin')->middleware(['role:super-admin'])->group(function () {
        Route::apiResource('categories', Admin\CategoryController::class);
        Route::apiResource('deliveries', Admin\DeliveryController::class);
        Route::apiResource('meals', Admin\MealController::class);
        Route::apiResource('meal-images', Admin\MealImageController::class);
        Route::apiResource('meal-inventories', Admin\MealInventoryController::class);
        Route::apiResource('orders', Admin\OrderController::class);
        Route::apiResource('order-items', Admin\OrderItemController::class);
        Route::apiResource('users', Admin\UserController::class);
        Route::apiResource('user-addresses', Admin\UserAddressController::class);
        Route::apiResource('user-images', Admin\UserImageController::class);
    });


    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::apiResource('categories', Admin\CategoryController::class)->except(['store', 'update', 'destroy']);
        Route::apiResource('deliveries', Admin\DeliveryController::class);
        Route::apiResource('meals', Admin\MealController::class)->except(['store', 'update', 'destroy']);
        Route::apiResource('meal-images', Admin\MealImageController::class);
        Route::apiResource('meal-inventories', Admin\MealInventoryController::class);
        Route::apiResource('orders', Admin\OrderController::class)->except(['destroy']);
        Route::apiResource('order-items', Admin\OrderItemController::class)->except(['update', 'destroy']);
        Route::apiResource('user-addresses', Admin\UserAddressController::class);
        Route::apiResource('user-images', Admin\UserImageController::class);
    });


    Route::prefix('rider')->middleware(['role:rider'])->group(function () {
        Route::apiResource('deliveries', Rider\DeliveryController::class)->only(['index', 'show', 'destroy']);
        Route::apiResource('user-addresses', Rider\UserAddressController::class);
        Route::apiResource('user-images', Rider\UserImageController::class)->except(['index']);
    });


    Route::prefix('user')->middleware(['role:generic-user'])->group(function () {
        Route::apiResource('categories', User\CategoryController::class)->only(['index', 'show']);
        Route::apiResource('meals', User\MealController::class)->only(['index', 'show']);
        Route::apiResource('meal-images', Admin\MealImageController::class)->only(['index', 'show']);
        Route::apiResource('orders', User\OrderController::class);
        Route::apiResource('order-items', User\OrderItemController::class);
        Route::apiResource('user-addresses', User\UserAddressController::class);
        Route::patch('user-addresses/{user_address}/make-default', [User\UserAddressController::class, 'makeDefault']);
        Route::apiResource('user-images', User\UserImageController::class)->except(['index']);
    });
});
