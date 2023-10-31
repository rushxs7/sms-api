<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\SendJobController;
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
    Route::get('/home', function(Request $request) {
        return view('dashboard');
    })->name('home');

    Route::get('/my-account/profile', [AccountController::class, 'myAccount'])->name('myaccount');
    // Profile Info Name + Email
    // Password Reset
    // API Keys
    Route::get('/my-account/finances', [AccountController::class, 'myFinances'])->name('myfinances');
    // Finances in
    // Finances out
    Route::prefix('/sms-service')->name('smsservice.')->group(function() {
        Route::get('/', [SendJobController::class, 'index'])->name('index');
        Route::get('/new', [SendJobController::class, 'create'])->name('create');
        Route::post('/new', [SendJobController::class, 'store'])->name('store');
        Route::get('/{job}/show', [SendJobController::class, 'show'])->name('show');
        Route::get('/{job}/edit', [SendJobController::class, 'edit'])->name('edit');
        Route::put('/{job}/update', [SendJobController::class, 'edit'])->name('update');
        Route::delete('/{job}/delete', [SendJobController::class, 'delete'])->name('delete');
    });
});

Auth::routes([
    'register' => false
]);

