<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/home', [ProductController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/product', [ProductController::class, 'viewAdmin'])->name('product');
    Route::post('/product/add', [ProductController::class, 'create'])->name('add.product');
    Route::post('/product/edit/{id}', [ProductController::class, 'edit'])->name('edit.product');
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete'])->name('delete.product');
});

Route::group(['middleware' => ['auth', 'user']], function () {
    Route::get('/product-list', [ProductController::class, 'viewUser'])->name('product.list');

    Route::get('/my-cart', [CartController::class, 'view'])->name('cart');
    Route::post('/add-to-cart/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/my-cart/delete/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/my-orders', [OrdersController::class, 'view'])->name('orders');
    Route::post('/check-out', [OrdersController::class, 'checkOut'])->name('check.out');
});

require __DIR__ . '/auth.php';
