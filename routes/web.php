<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SendJobController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\SendJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        return redirect('dashboard');
    }
    return redirect('login');

});
Route::middleware(['auth'])->group(function() {
    Route::get('/test', function(Request $request) {
        //
    });

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('/sms-service')->name('smsservice.')->group(function() {
        Route::get('/', [SendJobController::class, 'index'])->name('index');
        Route::get('/new', [SendJobController::class, 'create'])->name('create');
        Route::post('/new', [SendJobController::class, 'store'])->name('store');
        Route::get('/{sendJob}/show', [SendJobController::class, 'show'])->name('show');
        // Route::get('/{sendJob}/edit', [SendJobController::class, 'edit'])->name('edit');
        // Route::put('/{sendJob}/update', [SendJobController::class, 'update'])->name('update');
        // Route::delete('/{sendJob}/delete', [SendJobController::class, 'delete'])->name('delete');
    });

    Route::get('/my-account', [AccountController::class, 'myAccount'])->name('myaccount');
    Route::post('/my-account/tokens/create', [AccountController::class, 'createToken'])->name('myaccount.createtoken');
    Route::delete('/my-account/tokens/{tokenId}/revoke', [AccountController::class, 'revokeToken'])->name('myaccount.revoketoken');
    Route::post('/my-account/save-personal-info', [AccountController::class, 'savePersonalInfo'])->name('myaccount.savepersonalinfo');
    Route::post('/my-account/save-sender-info', [AccountController::class, 'saveSenderInfo'])->name('myaccount.savesenderinfo');
    Route::post('/my-account/password-reset', [AccountController::class, 'passwordReset'])->name('myaccount.passwordreset');

    Route::prefix('/my-organization')->group(function () {
        Route::middleware('permission:manage_organization_users')->group(function () {
            Route::get('/', [AccountController::class, 'myOrganization'])->name('myorg.index');
            Route::post('/users/new', [AccountController::class, 'newOrgUser'])->name('myorg.users.new');
            Route::get('/users/{user}/edit', [AccountController::class, 'editOrgUser'])->name('myorg.users.edit');
            Route::put('/users/{user}/update', [AccountController::class, 'updateOrgUser'])->name('myorg.users.update');
            Route::put('/users/{user}/updatePassword', [AccountController::class, 'updateOrgUserPassword'])->name('myorg.users.updatepassword');
            Route::delete('/users/{user}/delete', [AccountController::class, 'deleteOrgUser'])->name('myorg.users.delete');
        });
    });

    // API Keys

    Route::get('/my-credits', [AccountController::class, 'myCredits'])->name('mycredits');
    // Finances in
    // Finances out


    Route::middleware(['role:superadmin'])->group(function() {
        Route::resource('/users', UserController::class)->except(['show', 'create']);
        Route::put('/users/{user}/updatepassword', [UserController::class, 'updatePassword'])->name('users.updatepassword');
        Route::put('/users/{user}/updatedefaultsender', [UserController::class, 'updateDefaultSender'])->name('users.updatedefaultsender');

        Route::resource('/organizations', OrganizationController::class)->except(['show', 'create']);
    });

    Route::prefix('/webhooks')->group(function() {
        Route::get('/mope/qrcode', [TransactionController::class, 'generateQRCode'])->name('mope.generateqrcode');
        Route::get('/mope/link', [TransactionController::class, 'generatePaymentLink'])->name('mope.generateqrcode');
        Route::get('/uni5pay', [TransactionController::class, 'generateUni5payLink'])->name('uni5pay.generatelink');
    });
});

Auth::routes([
    'register' => false,
    'verify' => true
]);

