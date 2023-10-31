<?php

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
Route::get('/home', function(Request $request) {
    return view('home');
})->name('home');

Route::get('/my-account', function() {})->name('myaccount');
Route::prefix('/sms-service')->name('smsservice.')->group(function() {
    Route::get('/', function() {})->name('index');
    Route::get('/new', function() {})->name('create');
    Route::post('/new', function() {})->name('store');
    Route::post('/{job}/show', function() {})->name('show');
});

Auth::routes([
    'register' => false
]);

