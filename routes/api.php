<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// "token": "1|lFpfIeiRfxMGrbOWaYL1UBEFDLMbxkVt0Yv3ruDmf86fd190"
//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/search/{name}', [ProductController::class, 'search']);

//protected routes
Route::group(['middleware'=> ['auth:sanctum']], function(){
    Route::post('/products', [ProductController::class, 'store']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destory']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
