<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| FrontEnd - User Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'frontend_user', 'as' => 'frontend.'], function () {

    Auth::routes(['verify' => true]);


    Route::get('/', [FrontendController::class, 'index'])->name('index');
    Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
    Route::get('/detail', [FrontendController::class, 'detail'])->name('detail');
    Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
    Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');

});




