<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AddressController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/test', function(){
    return response()->json([
            "msg" => "hello world!"
    ]);
});

Route::middleware(['apitokencheck'])->group(function () {
    Route::get('/get/{id?}', [AddressController::class, 'getAddress']); 
    Route::post('/create', [AddressController::class, 'createAddress']); 
    Route::post('/update', [AddressController::class, 'updateAddress']); 
    Route::post('/delete', [AddressController::class, 'deleteAddress']);
});
