<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRLoginajaxPolling\QRLoginajaxPollingController;
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
Route::post('/login/create/qrcode', [QRLoginajaxPollingController::class,'CreateQrcodeAction']);
Route::post('/login/qrcodedoLogin', [QRLoginajaxPollingController::class,'qrcodeDoLoginAction']); //this url is used when qr code is scanned successfully
Route::post('/login/mobile/scan/qrcode', [QRLoginajaxPollingController::class,'mobileScanQrcodeAction']);
Route::post('/login/scan/qrcode', [QRLoginajaxPollingController::class,'isScanQrcodeAction']);