<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/products', 'pages::products')->name('products');
Route::livewire('/products/{product}', 'pages::product-detail')->name('product.detail');
Route::livewire('/cart', 'pages::cart')->name('cart');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/login', 'pages::admin-login')->name('login')->middleware('guest');

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::livewire('/dashboard', 'pages::admin-dashboard')->name('dashboard');
        Route::livewire('/products', 'pages::admin-products')->name('products');
        Route::livewire('/categories', 'pages::admin-categories')->name('categories');

        Route::post('/logout', function () {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('admin.login');
        })->name('logout');
    });
});
