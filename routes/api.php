<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::group(['prefix' => 'v1'], function () {
    Route::post('country',   [\App\Http\Controllers\Api\V1\SettingController::class,'country'])->name('country');
    Route::any('setting', [\App\Http\Controllers\Api\V1\SettingController::class,'setting']);
    Route::post('about', [\App\Http\Controllers\Api\V1\SettingController::class,'about']);
    Route::any('cities', [\App\Http\Controllers\Api\V1\SettingController::class,'getCities']);
    Route::post('regions/{id}', [\App\Http\Controllers\Api\V1\SettingController::class,'getAreas']);
    Route::post('contact', [\App\Http\Controllers\Api\V1\SettingController::class,'contact']);
 //ads
    Route::post('ads', [\App\Http\Controllers\Api\V1\SettingController::class,'ads']);
    Route::post('categories', [\App\Http\Controllers\Api\V1\SettingController::class,'getData']);
    Route::post('packages', [\App\Http\Controllers\Api\V1\SettingController::class,'packages']);
    Route::post('payment', [\App\Http\Controllers\Api\V1\SettingController::class,'payment']);

    Route::post('/getFav', [\App\Http\Controllers\Api\V1\SettingController::class,'getFav'])->middleware('api');
    Route::post('/addFav', [\App\Http\Controllers\Api\V1\SettingController::class,'addFav'])->middleware('api');
    Route::post('/deleteFav', [\App\Http\Controllers\Api\V1\SettingController::class,'deleteFav'])->middleware('api');


    Route::post('doctors', [\App\Http\Controllers\Api\V1\HomeController::class,'doctors']);
    Route::post('doctorsProfile', [\App\Http\Controllers\Api\V1\HomeController::class,'doctorsProfile']);
    Route::post('search', [\App\Http\Controllers\Api\V1\HomeController::class,'search']);
    Route::get('home', [\App\Http\Controllers\Api\V1\HomeController::class,'home']);


});

Route::group(['prefix' => 'address', 'middleware' => ['api']], function () {

    Route::post('/add', [\App\Http\Controllers\Api\V1\SettingController::class,'addAdd'])->middleware('api');
    Route::post('/get', [\App\Http\Controllers\Api\V1\SettingController::class,'getAdd'])->middleware('api');
    Route::post('/delete', [\App\Http\Controllers\Api\V1\SettingController::class,'deleteAdd'])->middleware('api');
    Route::post('/edit', [\App\Http\Controllers\Api\V1\SettingController::class,'editAdd'])->middleware('api');

});


Route::post('home', [\App\Http\Controllers\Api\V3\ClothesController::class,'home']);


Route::get('get-user', [\App\Http\Controllers\Api\V1\UserController::class,'getUser']);



Route::group(['prefix' => 'user', 'middleware' => ['api']], function () {


    Route::post('auth', ['as' => 'api-auth', 'uses' => 'Api\V1\AuthController@auth']);
    Route::post('forgetPass', ['as' => 'api-forget-pass', 'uses' => 'Api\V1\AuthController@forgetPassword']);
    Route::post('verifyCode', ['as' => 'api-auth-verify-ode', 'uses' => 'Api\V1\AuthController@verifyCode']);

    Route::post('auth', [\App\Http\Controllers\Api\V1\AuthController::class,'auth']);
    Route::post('register', [\App\Http\Controllers\Api\V1\UserController::class,'register'])->middleware('throttle:2,1');

    Route::post('auth/refresh', [\App\Http\Controllers\Api\V1\AuthController::class,'refreshToken']);

    Route::post('update/token',[\App\Http\Controllers\Api\V1\UserController::class,'tokenFcm'])->middleware('api');


    Route::post('activateAccount', [\App\Http\Controllers\Api\V1\UserController::class,'activateAccount'])->middleware('api');
    Route::post('resendActivation', [\App\Http\Controllers\Api\V1\UserController::class,'resendActivation'])->middleware('api');
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class,'logout'])->middleware('api');


    Route::post('profile', [\App\Http\Controllers\Api\V1\UserController::class,'profile'])->middleware('api');
    Route::post('update', [\App\Http\Controllers\Api\V1\UserController::class,'updateInfo'])->middleware('api');

    Route::post('orderPackages', [\App\Http\Controllers\Api\V1\UserController::class,'OrderPackage'])->middleware('api');
    Route::post('orderStatus', [\App\Http\Controllers\Api\V1\UserController::class,'orderStatus'])->middleware('api');


    Route::post('/getFollow', [\App\Http\Controllers\Api\V1\UserController::class,'getFollow'])->middleware('api');
    Route::post('/addFollow', [\App\Http\Controllers\Api\V1\UserController::class,'addFollow'])->middleware('api');
    Route::post('/deleteFollow', [\App\Http\Controllers\Api\V1\UserController::class,'deleteFollow'])->middleware('api');


    Route::post('/reportUser', [\App\Http\Controllers\Api\V1\UserController::class,'reportUser'])->middleware('api');
        Route::post('/blockUser', [\App\Http\Controllers\Api\V1\UserController::class,'BlockUser'])->middleware('api');
        Route::post('/deleteUser', [\App\Http\Controllers\Api\V1\UserController::class,'deleteUser'])->middleware('api');



    Route::any('notification', [\App\Http\Controllers\Api\V1\NotificationController::class,'getData'])->middleware('api');
    Route::any('chat/notification', [\App\Http\Controllers\Api\V1\NotificationController::class,'chat'])->middleware('api');
    Route::post('notification/product', [\App\Http\Controllers\Api\V1\NotificationController::class,'multi_product'])->middleware('api');
    Route::get('read/{id}', [\App\Http\Controllers\Api\V1\NotificationController::class,'read'])->middleware('api');
    Route::post('notification/views', [\App\Http\Controllers\Api\V1\NotificationController::class,'views']);


    Route::post('/connection', [\App\Http\Controllers\Api\V1\UserController::class,'connection_count']);






});


Route::group(['prefix' => 'advertisements', 'middleware' => ['api']], function () {
    Route::post('createProperty', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'createProperty'])->middleware('api');
    Route::post('updateProperty', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'updateProperty'])->middleware('api');
    Route::get('deleteProperty/{id}', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'deleteProperty'])->middleware('api');
    Route::get('deleteImage/{id}', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'deleteImage'])->middleware('api');
    Route::post('details', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'getRow']);



    Route::post('index/{cat_id}', [\App\Http\Controllers\Api\V1\ClothesController::class,'index']);
    Route::post('index_all', [\App\Http\Controllers\Api\V1\ClothesController::class,'all']);




});

Route::get('advertisements/{id}', [\App\Http\Controllers\Api\V1\AdvertisementsController::class,'getProperty']);


Route::get('callback/success',   [\App\Http\Controllers\Api\V1\CountriesController::class,'ordersSuccess'])->name('ordersSuccess');
Route::get('callback/error',   [\App\Http\Controllers\Api\V1\CountriesController::class,'ordersError'])->name('ordersError');

Route::post('paymentStatus',   [\App\Http\Controllers\Api\V1\UserController::class,'paymentStatus'])->name('paymentStatus');

Route::post('cities', ['as' => 'api-get-cities', 'middleware' => ['api', 'settings', 'https'], 'uses' => 'Api\V1\CountriesController@getRegion']);

Route::post('contactUs', [\App\Http\Controllers\Api\V1\ClothesController::class,'contactUs']);

