<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Control\ControlController;

use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Support\SupportController;

use Illuminate\Support\Facades\Route;


//mail test

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Route::get('/mail', function () {
    Mail::to('dpumaticona@gmail.com')->send(new TestMail());
    return 'Email was sent';
});

//view test mail
Route::get('/test-mail', function () {
    return view('mail.test');
});


Route::get('/', [CustomersController::class, 'index']);
Route::get('/sale/{id}', [CustomersController::class, 'sale']);
Route::get('/payment', [CustomersController::class, 'payment']);
Route::post('/sales', [SaleController::class, 'storeOnline'])->name('store.online');


Route::name('auth.')->prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
    Route::post('/sign-in', [AuthController::class, 'signIn'])->name('sign-in')->middleware('guest');
    Route::get('/sign-up', [AuthController::class, 'signUp'])->name('sign-up');
    Route::post('/sign-out', [AuthController::class, 'signOut'])->name('sign-out')->middleware('auth');
});


Route::middleware(['auth', 'admin'])->name('a.')->prefix('a')->group(function () {
    Route::get('',  [AdminController::class, 'index'])->name('index');

    // Route::resource('users', AdminController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('events', EventController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
});

Route::middleware(['auth', 'support'])->name('su.')->prefix('su')->group(function () {
    Route::get('',  [SupportController::class, 'index'])->name('index');

    //get all events
    Route::get('/events', [SupportController::class, 'events'])->name('events');
});

Route::middleware(['auth', 'sales'])->name('sa.')->prefix('sa')->group(function () {
    Route::get('',  [SalesController::class, 'index'])->name('index');
    Route::get('/events', [SalesController::class, 'events'])->name('events');
    Route::get('/events/{id}', [SalesController::class, 'show'])->name('show');
    Route::post('/sales', [SaleController::class, 'store'])->name('store');

    //cancel
    Route::put('/sales/{id}/cancel', [SaleController::class, 'cancel'])->name('cancel');

    //confirmOnlinePayment
    Route::put('/sales/{id}/confirm-online-payment', [SaleController::class, 'confirmOnlinePayment'])->name('confirm-online-payment');
});

Route::middleware(['auth', 'control'])->name('co.')->prefix('co')->group(function () {
    Route::get('',  [ControlController::class, 'index'])->name('index');
});
