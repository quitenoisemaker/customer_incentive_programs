<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}/referrals', [UserController::class, 'fetchUserReferral']);
});
