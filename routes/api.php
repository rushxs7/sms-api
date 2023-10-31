<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SmsController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/sms')->group(function() {
        Route::post('/send/now', [SmsController::class, 'sendNow'])->name('sms.sendnow');
        Route::post('/send/later', [SmsController::class, 'sendLater'])->name('sms.sendlater');
        Route::post('/cancel/{smsUuid}', [SmsController::class, 'cancelSms'])->name('sms.sendlater');
        Route::post('/bulksend/now', [SmsController::class, 'bulkSendNow'])->name('sms.bulksendnow');
        Route::post('/bulksend/later', [SmsController::class, 'bulkSendLater'])->name('sms.bulksendlater');
        Route::post('/bulksend/cancel/{bulkUuid}', [SmsController::class, 'bulkSendLater'])->name('sms.bulksendlater');
    });

    Route::prefix('/history')->group(function() {
        Route::get('/', [HistoryController::class, 'index'])->name('history.index');
        Route::get('/{smsUuid}', [HistoryController::class, 'show'])->name('history.show');
        Route::get('/{smsUuid}/isSent', [HistoryController::class, 'isSent'])->name('history.isSent');
    });
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
