<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRLoginajaxPolling\QRLoginajaxPollingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/qrtest', function () {
    return view('qrlogin.showqr');
});
Route::get('/qrscanner',[QRLoginajaxPollingController::class,'qrscanner'])->name('qrscanner');
Route::post('web/login/entry/login', [QRLoginajaxPollingController::class,'loginEntry']);//Check whether the login has been confirmed ,and return the token in response


require __DIR__.'/auth.php';
