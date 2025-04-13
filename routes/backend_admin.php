<?php

use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CustomerAddress\CustomerAddressController;
use App\Http\Controllers\Backend\Customers\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductCategories\ProductCategoriesController;
use App\Http\Controllers\Backend\ProductCoupons\ProductCouponController;
use App\Http\Controllers\Backend\ProductReviews\ProductReviewController;
use App\Http\Controllers\Backend\Products\ProductController;
use App\Http\Controllers\Backend\ShippingCompanies\ShippingCompanyController;
use App\Http\Controllers\Backend\Supervisors\SupervisorController;
use App\Http\Controllers\Backend\Tags\TagController;
use App\Http\Controllers\Backend\Worlds\CityController;
use App\Http\Controllers\Backend\Worlds\CountryController;
use App\Http\Controllers\Backend\Worlds\StateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| BackEnd - Admin Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['verify' => true]);


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {



    //////////// This route group is for the admin login and forgot_password //////////////////////////
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
        //Route::get('/register', [LoginController::class, 'registerForm'])->name('register')->middleware('guest');
        Route::get('/forgot_password', [LoginController::class, 'forgotPasswordForm'])->name('forgot_password');
    });




    ////////// This route group is for the admin dashboard /////////////////////////////////////////////////
    Route::group(['middleware' => ['roles', 'role:admin|supervisor']], function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::get('/account_settings', [DashboardController::class, 'account_settings'])->name('account_settings');
        Route::post('/admin/remove-image', [DashboardController::class, 'remove_image'])->name('remove_image');
        Route::patch('/account_settings', [DashboardController::class, 'update_account_settings'])->name('update_account_settings');


        //////////  Product Categories Routes  //////////////////////////////////////////////////////////////
        Route::resource('product_categories', ProductCategoriesController::class);
        Route::post('product_categories/remove-image', [ProductCategoriesController::class, 'remove_image'])->name('product_categories.remove_image');

        ///////// Products Routes /////////////////////////////////////////////////////////////////////////
        Route::resource('products', ProductController::class);
        Route::post('products/remove-image', [ProductController::class, 'remove_image'])->name('products.remove_image');


        ////////// Tags Routes //////////////////////////////////////////////////////////////////////////////
        Route::resource('tags', TagController::class);

        //////////  Product Coupons Routes  //////////////////////////////////////////////////////////////
        Route::resource('product_coupons', ProductCouponController::class);

        //////////  Product Coupons Routes  //////////////////////////////////////////////////////////////
        Route::resource('product_reviews', ProductReviewController::class);


        //////////   Customers Routes  //////////////////////////////////////////////////////////////
        Route::post('customers/remove-image', [CustomerController::class, 'remove_image'])->name('customers.remove_image');
        Route::get('/customers/get_customers', [CustomerController::class, 'get_customers'])->name('customers.get_customers');
        Route::resource('customers', CustomerController::class);

        //////////   Supervisor Routes  //////////////////////////////////////////////////////////////
        Route::resource('supervisors', SupervisorController::class);
        Route::post('supervisor/remove-image', [SupervisorController::class, 'remove_image'])->name('supervisors.remove_image');

        //////////   Countries Routes  //////////////////////////////////////////////////////////////
        Route::resource('countries', CountryController::class);

        //////////   States Routes  //////////////////////////////////////////////////////////////
        Route::get('states/get_states', [StateController::class, 'get_states'])->name('states.get_states');
        Route::resource('states', StateController::class);


        //////////   Cities Routes  //////////////////////////////////////////////////////////////
        Route::get('cities/get_cities', [CityController::class, 'get_cities'])->name('cities.get_cities');
        Route::resource('cities', CityController::class);


        //////////   Customer Addresses Routes  //////////////////////////////////////////////////////////////
        Route::resource('customer_addresses', CustomerAddressController::class);

        //////////   Shipping Companies Routes  //////////////////////////////////////////////////////////////
        Route::resource('shipping_companies', ShippingCompanyController::class);


    });
});
