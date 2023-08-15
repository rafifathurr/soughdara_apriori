<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// ALL CONTROLLERS

Route::get('/', function () {
    if(Auth::guard('admin')->check()){
    	return redirect()->route('admin.dashboard.index');
    } else {
        if(Auth::guard('user')->check()){
            return redirect()->route('user.order.index');
        } else {
            return redirect()->route('menu');
        }
    }
});

Route::namespace('App\Http\Controllers')->group(function (){

    Route::get('/menu', 'home\HomeControllers@home')->name('menu');
    Route::get('/menu/category/{id}', 'home\HomeControllers@category')->name('menu.category');

    Route::namespace('login')->prefix('auth')->name('login.')->group(function () {
        Route::get('/login', 'LoginController@index')->name('index');
        Route::post('/login', 'LoginController@authenticate')->name('authenticate');
        Route::post('/logout', 'LoginController@logout')->name('logout');
    });

    Route::namespace('forgot')->prefix('auth')->name('forgot.')->group(function () {
        Route::get('/forgot', 'ForgotControllers@index')->name('index');
        Route::post('/forgot', 'ForgotControllers@updatepass')->name('updatepass');
    });

});

Route::namespace('App\Http\Controllers')->group(function (){

    Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
        // ROUTE TO DASHBOARD CONTROLLERS
        Route::namespace('dashboard')->name('dashboard.')->group(function () {
            Route::get('/dashboard-admin', 'DashboardControllers@index')->name('index');
        });

        // ROUTE TO ANALYSIS CONTROLLERS
        Route::namespace('analysis')->prefix('analysis')->name('analysis.')->group(function () {
            Route::get('/', 'AnalysisControllers@index')->name('index');
            Route::get('create/{date}/{support}/{confidence}', 'AnalysisControllers@create')->name('create');
            Route::post('getMonth', 'AnalysisControllers@getMonth')->name('getMonth');
            Route::post('store', 'AnalysisControllers@store')->name('store');
            Route::post('delete', 'AnalysisControllers@delete')->name('delete');
            Route::get('detail/{id}', 'AnalysisControllers@detail')->name('detail');
        });

        // ROUTE TO ORDER CONTROLLERS
        Route::namespace('order')->prefix('order')->name('order.')->group(function () {
            Route::get('/', 'OrderControllers@index')->name('index');
            Route::get('create', 'OrderControllers@create')->name('create');
            Route::post('store', 'OrderControllers@store')->name('store');
            Route::get('getDetailProds', 'OrderControllers@getDetailProds')->name('getDetailProds');
            Route::get('detail/{id}', 'OrderControllers@detail')->name('detail');
            Route::get('edit/{id}', 'OrderControllers@edit')->name('edit');
            Route::post('update', 'OrderControllers@update')->name('update');
            Route::post('delete', 'OrderControllers@delete')->name('delete');
            Route::post('getMonth', 'OrderControllers@getMonth')->name('getMonth');
            Route::post('export', 'OrderControllers@export')->name('export');
        });

        // ROUTE TO PRODUCTS CONTROLLERS
        Route::namespace('product')->prefix('product')->name('product.')->group(function () {
            Route::get('/', 'ProductControllers@index')->name('index');
            Route::get('create', 'ProductControllers@create')->name('create');
            Route::post('store', 'ProductControllers@store')->name('store');
            Route::get('detail/{id}', 'ProductControllers@detail')->name('detail');
            Route::get('edit/{id}', 'ProductControllers@edit')->name('edit');
            Route::post('update', 'ProductControllers@update')->name('update');
            Route::post('delete', 'ProductControllers@delete')->name('delete');
            Route::get('detailproduct', 'ProductControllers@detailproduct')->name('detailproduct');
        });

        // ROUTE TO PAYMENT METHOD CONTROLLERS
        Route::namespace('payment_method')->prefix('payment-method')->name('payment_method.')->group(function () {
            Route::get('/', 'PaymentMethodController@index')->name('index');
            Route::get('create', 'PaymentMethodController@create')->name('create');
            Route::post('store', 'PaymentMethodController@store')->name('store');
            Route::get('detail/{id}', 'PaymentMethodController@detail')->name('detail');
            Route::get('edit/{id}', 'PaymentMethodController@edit')->name('edit');
            Route::post('update', 'PaymentMethodController@update')->name('update');
            Route::post('delete', 'PaymentMethodController@delete')->name('delete');
        });

        // ROUTE TO CATEGORY CONTROLLERS
        Route::namespace('category')->prefix('category')->name('category.')->group(function () {
            Route::get('/', 'CategoryControllers@index')->name('index');
            Route::get('create', 'CategoryControllers@create')->name('create');
            Route::post('store', 'CategoryControllers@store')->name('store');
            Route::get('detail/{id}', 'CategoryControllers@detail')->name('detail');
            Route::get('edit/{id}', 'CategoryControllers@edit')->name('edit');
            Route::post('update', 'CategoryControllers@update')->name('update');
            Route::post('delete', 'CategoryControllers@delete')->name('delete');
        });

        // ROUTE TO USERS CONTROLLERS
        Route::namespace('users')->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'UsersControllers@index')->name('index');
            Route::get('create', 'UsersControllers@create')->name('create');
            Route::post('store', 'UsersControllers@store')->name('store');
            Route::get('detail/{id}', 'UsersControllers@detail')->name('detail');
            Route::get('edit/{id}', 'UsersControllers@edit')->name('edit');
            Route::post('update', 'UsersControllers@update')->name('update');
            Route::post('delete', 'UsersControllers@delete')->name('delete');
        });

        // ROUTE TO USER ROLES CONTROLLERS
        Route::namespace('role')->prefix('role')->name('role.')->group(function () {
            Route::get('/', 'RoleControllers@index')->name('index');
            Route::get('create', 'RoleControllers@create')->name('create');
            Route::post('store', 'RoleControllers@store')->name('store');
            Route::get('detail/{id}', 'RoleControllers@detail')->name('detail');
            Route::get('edit/{id}', 'RoleControllers@edit')->name('edit');
            Route::post('update', 'RoleControllers@update')->name('update');
            Route::post('delete', 'RoleControllers@delete')->name('delete');
        });
    });

    Route::middleware('auth:user')->prefix('user')->name('user.')->group(function () {

        // ROUTE TO ORDER CONTROLLERS
        Route::namespace('order')->prefix('order')->name('order.')->group(function () {
            Route::get('/', 'OrderControllers@index')->name('index');
            Route::get('create', 'OrderControllers@create')->name('create');
            Route::post('store', 'OrderControllers@store')->name('store');
            Route::get('getDetailProds', 'OrderControllers@getDetailProds')->name('getDetailProds');
            Route::get('detail/{id}', 'OrderControllers@detail')->name('detail');
            Route::get('edit/{id}', 'OrderControllers@edit')->name('edit');
            Route::post('update', 'OrderControllers@update')->name('update');
            Route::post('delete', 'OrderControllers@delete')->name('delete');
        });

        // ROUTE TO PRODUCT CONTROLLERS
        Route::namespace('product')->prefix('product')->name('product.')->group(function () {
            Route::get('/', 'ProductControllers@index')->name('index');
            Route::get('create', 'ProductControllers@create')->name('create');
            Route::post('store', 'ProductControllers@store')->name('store');
            Route::get('detail/{id}', 'ProductControllers@detail')->name('detail');
            Route::get('edit/{id}', 'ProductControllers@edit')->name('edit');
            Route::post('update', 'ProductControllers@update')->name('update');
            Route::post('delete', 'ProductControllers@delete')->name('delete');
        });
    });
});


