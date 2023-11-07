<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SendJobController;
use App\Models\SendJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    if (Auth::check()) {
        return redirect('home');
    }
    return redirect('login');

});
Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    Route::get('/my-account', [AccountController::class, 'myAccount'])->name('myaccount');
    Route::post('/my-account/save-personal-info', [AccountController::class, 'savePersonalInfo'])->name('myaccount.savepersonalinfo');
    Route::post('/my-account/password-reset', [AccountController::class, 'passwordReset'])->name('myaccount.passwordreset');
    // Profile Info Name + Email
    // Password Reset
    // API Keys
    Route::get('/my-credits', [AccountController::class, 'myCredits'])->name('mycredits');
    // Finances in
    // Finances out
    Route::prefix('/sms-service')->name('smsservice.')->group(function() {
        Route::get('/', [SendJobController::class, 'index'])->name('index');
        Route::get('/new', [SendJobController::class, 'create'])->name('create');
        Route::post('/new', [SendJobController::class, 'store'])->name('store');
        Route::get('/{sendJob}/show', [SendJobController::class, 'show'])->name('show');
        // Route::get('/{sendJob}/edit', [SendJobController::class, 'edit'])->name('edit');
        // Route::put('/{sendJob}/update', [SendJobController::class, 'update'])->name('update');
        // Route::delete('/{sendJob}/delete', [SendJobController::class, 'delete'])->name('delete');
    });
});

Auth::routes([
    'register' => false
]);

