<?php

use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/', fn() => view('seller.dashboard'))->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', SellerOrderController::class)->only(['index', 'show', 'update']);
    });

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/c/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/p/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'mine'])->name('orders.mine');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::view('/about', 'static.about')->name('static.about');
Route::view('/contact', 'static.contact')->name('static.contact');
Route::view('/terms', 'static.terms')->name('static.terms');


require __DIR__ . '/settings.php';
