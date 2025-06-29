<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Iuran\PembayaranController;
use App\Http\Controllers\Iuran\PengeluaranController;
use App\Http\Controllers\Iuran\TagihanController;
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

Route::middleware('guest')->post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/rumah/get_data', [RumahController::class, 'get_data']);

Route::get('/rumah/', [RumahController::class, 'get_rumah']);

Route::get('/rumah/get/{id}', [RumahController::class, 'rumah_detail']);

Route::post('/rumah/update/{id}', [RumahController::class, 'update']);


Route::post('/penghuni/store/{id}', [PenghuniController::class, 'store']);

Route::post('/penghuni/update/{id}', [PenghuniController::class, 'update']);

Route::post('/penghuni/remove/{id}', [PenghuniController::class, 'remove']);

Route::get('/penghuni', [PenghuniController::class, 'get_penghuni']);

Route::get('/penghuni/detail/{id}', [PenghuniController::class, 'penghuni_detail']);


Route::get('/tagihan/', [TagihanController::class, 'get_Tagihan']);

Route::get('/tagihan/detail/{id}', [TagihanController::class, 'detail_Tagihan']);

Route::post('/pembayaran/kebersihan/{id}', [PembayaranController::class, 'pembayaran_kebersihan']);

Route::post('/pembayaran/satpam/{id}', [PembayaranController::class, 'pembayaran_satpam']);


Route::get('/pengeluaran/', [PengeluaranController::class, 'get_data']);

Route::post('/pengeluaran/input', [PengeluaranController::class, 'pengeluaran']);

Route::get('/rekapData/get', [TagihanController::class, 'getRekapData']);

Route::get('/rekapData/detail/get', [TagihanController::class, 'getDetailData']);



