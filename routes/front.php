<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\LandingPageController;
use App\Http\Controllers\Front\AddsController;
use App\Http\Controllers\Front\SubscribeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\SellerController;
use App\Http\Controllers\Front\ClassificationController;
use App\Http\Controllers\Front\SiteUserController;
use App\Http\Controllers\Front\ChatController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),

    ], function () {
    Route::get('/', [LandingPageController::class, 'index'])->name("index");
    Route::get('privacyPolicy', [LandingPageController::class, 'privacy'])->name("privacy");
    Route::get('subscribe', [SubscribeController::class, 'index'])->name("subscribe");
    Route::get('filter', [ClassificationController::class, 'filter'])->name("filter");
    Route::get('classification', [ClassificationController::class, 'index'])->name("classification");

    // Product Routes
    Route::get('detail_product', [ProductController::class, 'show'])->name("DetailProduct");
    Route::group(['middleware' => ['auth:user']], function () {
        Route::get('EditProduct/{id}/{idUser}', [AddsController::class, 'Edit'])->name("EditProduct");
    });
    // Api Register
    Route::post('api-register', [SiteUserController::class, 'register'])->name("apiRegister");
    Route::post('api-verify', [SiteUserController::class, 'activateAccount'])->name("apiVerify");

    // Seller Routes
    Route::get('seller', [SellerController::class, 'show'])->name("seller");

    // User Routes
    Route::get('user_logout', [SiteUserController::class, 'user_logout'])->name("user_logout");
    Route::get('user_Profile', [SiteUserController::class, 'ProfileUser'])->name("user_Profile");

    // Chat Routes
    Route::get('chat/{id}', [ChatController::class, 'index'])->name("chat");

    Route::group(['middleware' => ['api','auth:user']], function () {

        Route::get('adds', [AddsController::class, 'create'])->name("adds")->middleware('api');

        Route::post('fav', [LandingPageController::class, 'fav'])->name("front.fav")->middleware('api');

        Route::get('adds', [AddsController::class, 'create'])->name("adds")->middleware('api');

        Route::get('city_filter', [AddsController::class, 'getCity'])->name("cityFilter")->middleware('api');
        Route::get('subcat_filter', [AddsController::class, 'getSubCat'])->name("catFilter")->middleware('api');
        Route::get('getSubCatEdit', [AddsController::class, 'getSubCatEdit'])->name("getSubCatEdit")->middleware('api');
        Route::get('input-filter', [AddsController::class, 'getInputs'])->name("inputFilter")->middleware('api');
        Route::post('add-store', [AddsController::class, 'store'])->name("addStore")->middleware('api');

    });
});






