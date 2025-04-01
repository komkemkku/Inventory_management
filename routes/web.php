<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// order
Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.index')
    ->middleware('auth');

Route::get('/orders/create', [OrderController::class, 'create'])
    ->name('orders.create')
    ->middleware('auth');

Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])
    ->name('orders.edit')
    ->middleware('auth');

Route::get('/orders/{id}', [OrderController::class, 'destroy'])
    ->name('orders.destroy')
    ->middleware('auth');

Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store')
    ->middleware('auth');


// order detail
Route::get('/order-details/{orderId}', [OrderDetailController::class, 'index'])
    ->name('orderDetails.index')
    ->middleware('auth');

Route::get('/order-details/{orderId}/create', [OrderDetailController::class, 'create'])
    ->name('orderDetails.create')
    ->middleware('auth');

Route::post('/order-details', [OrderDetailController::class, 'store'])
    ->name('orderDetails.store')
    ->middleware('auth');

Route::get('/order-details/{id}/edit', [OrderDetailController::class, 'edit'])
    ->name('orderDetails.edit')
    ->middleware('auth');

Route::put('/order-details/{id}', [OrderDetailController::class, 'update'])
    ->name('orderDetails.update')
    ->middleware('auth');

Route::delete('/order-details/{id}', [OrderDetailController::class, 'destroy'])
    ->name('orderDetails.destroy')
    ->middleware('auth');


// Customer
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index')
    ->middleware('auth');

Route::get('/customers/create', [CustomerController::class, 'create'])
    ->name('customers.create')
    ->middleware('auth');

Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])
    ->name('customers.edit')
    ->middleware('auth');

Route::put('/customers/{id}', [CustomerController::class, 'update'])
    ->name('customers.update')
    ->middleware('auth');

Route::get('/customers/{id}', [CustomerController::class, 'destroy'])
    ->name('customers.destroy')
    ->middleware('auth');

Route::post('/customers', [CustomerController::class, 'store'])
    ->name('customers.store')
    ->middleware('auth');


// goods
Route::get('/goods', [GoodsController::class, 'index'])
    ->name('goods.index')
    ->middleware('auth');

Route::get('/goods/create', [GoodsController::class, 'create'])
    ->name('goods.create')
    ->middleware('auth');

Route::get('/goods/{id}/edit', [GoodsController::class, 'edit'])
    ->name('goods.edit')
    ->middleware('auth');

Route::put('/goods/{id}', [GoodsController::class, 'update'])
    ->name('goods.update')
    ->middleware('auth');

Route::delete('/goods/{id}', [GoodsController::class, 'destroy'])
    ->name('goods.destroy')
    ->middleware('auth');

Route::post('/goods', [GoodsController::class, 'store'])
    ->name('goods.store')
    ->middleware('auth');


// report
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index')
    ->middleware('auth');

Route::get('/reports/show', [ReportController::class, 'show'])
    ->name('reports.show')
    ->middleware('auth');
