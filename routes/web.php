<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\OrderController;
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

Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store')
    ->middleware('auth');


// Customer
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index')
    ->middleware('auth');

Route::get('/customers/create', [CustomerController::class, 'create'])
    ->name('customers.create')
    ->middleware('auth');

Route::get('/customers/update', [CustomerController::class, 'update'])
    ->name('customers.update')
    ->middleware('auth');

Route::get('/customers/destroy', [CustomerController::class, 'destroy'])
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
