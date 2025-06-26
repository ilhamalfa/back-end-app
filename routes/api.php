<?php

use App\Http\Controllers\Penghuni\PenghuniController;
use App\Http\Controllers\Rumah\RumahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/penghuni/store/{id}', [PenghuniController::class, 'store']);

Route::post('/penghuni/update/{id}', [PenghuniController::class, 'update']);

Route::post('/penghuni/remove/{id}', [PenghuniController::class, 'remove']);


Route::post('/rumah/store', [RumahController::class, 'store']);

