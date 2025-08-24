<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\V1\ListingController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function ()
{
    // Guest Route
    Route::middleware('guest')->group(function ()
    {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    // Api Route with token
    Route::middleware('auth:api')->get('/user', function (Request $request)
    {
        return new UserResource($request->user());
    });

    // Api Route with sanctum
    Route::middleware(['auth:sanctum', 'verified'])->group(function ()
    {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
    });

    // Test API route (no auth required)
    Route::get('test-user', function () {
        return response()->json([
            'name' => 'ahmad',
            'last_name' => 'ahmadi'
        ]);
    });

    // COMPLETE LISTING API ROUTES
    Route::get('listings', [ListingController::class, 'index']);
    Route::post('listings', [ListingController::class, 'store']);
    Route::get('listings/{id}', [ListingController::class, 'show']);
    Route::put('listings/{id}', [ListingController::class, 'update']);
    Route::delete('listings/{id}', [ListingController::class, 'destroy']);
});