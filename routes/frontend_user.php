<?php

use App\Http\Controllers\Frontend\Customers\CustomerController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PaymentMethod\PaymentController;
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
    Route::get('/shop/{slugCategory?}/{slugTag?}', [FrontendController::class, 'shop'])->name('shop');
//    Route::get('/shop/tags/{slug}', [FrontendController::class, 'shop_tag'])->name('shop_tag');
    Route::get('/product/{slug}', [FrontendController::class, 'product'])->name('product');
    Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
    Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist');


    Route::group(['middleware' => ['roles', 'role:customer']], function () {

        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

        Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
        Route::patch('/profile', [CustomerController::class, 'update_profile'])->name('customer.update_profile');
        Route::get('/profile/remove-image', [CustomerController::class, 'remove_profile_image'])->name('customer.remove_profile_image');
        Route::get('/addresses', [CustomerController::class, 'addresses'])->name('customer.addresses');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');

        Route::group(['middleware' => 'check_cart'], function () {

            Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
            Route::post('/checkout/payment', [PaymentController::class, 'checkout_now'])->name('checkout.payment');

            Route::get('/checkout/{order_id}/cancelled', [PaymentController::class, 'cancelled'])->name('checkout.cancel');
            Route::get('/checkout/{order_id}/completed', [PaymentController::class, 'completed'])->name('checkout.complete');
            Route::get('/checkout/webhook/{order?}/{env?}', [PaymentController::class, 'webhook'])->name('checkout.webhook.ipn');
        });

    });

});




