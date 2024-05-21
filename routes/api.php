<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserReferralProgramController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/users')->group(function () {
    Route::post('/', [UserReferralProgramController::class, 'store']);
    Route::get('/{user}/referrals', [UserReferralProgramController::class, 'fetchUserReferral']);
});
