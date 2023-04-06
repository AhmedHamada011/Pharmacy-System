<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\AddressController;
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

Route::post("register",[AuthController::class, 'register']);
Route::post('/sanctum/token', [AuthController::class, 'getToken']);









Route::group(["middleware"=>"auth:sanctum"],function (){

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

        $request->fulfill();
        return response()->json([
            "message" => "your email has been verified"
        ]);
    })->middleware(['signed'])->name('api.verification.verify');


    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotificationApi();

        return response()->json([
            'message' => 'Verification link sent!'
            ]);
    })->middleware(['throttle:6,1'])->name('api.verification.send');


    Route::put('/users/{user}',[UserController::class, 'update']);

    Route::get('/addresses', [AddressController::class, 'index']);
    Route::get('/addresses/{address}',[AddressController::class, 'show']);
    Route::put('/addresses/{address}',[AddressController::class, 'update']);
    Route::post('/addresses',[AddressController::class , 'store']);
    Route::delete('/addresses/{address}',[AddressController::class, 'destroy']);

    Route::get('/orders' , [OrderController::class , 'index']);
    Route::get('/orders/{order}' , [OrderController::class , 'show']);
    Route::post('/orders' , [OrderController::class , 'store']);
    Route::put('/orders/{order}' , [OrderController::class , 'update']);

});


