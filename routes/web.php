<?php

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

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


use Illuminate\Support\Facades\Route;

Route::post('/sign-in', 'UsersController@sign_in');
Route::post('/sign-up', 'UsersController@register')->middleware('admin');
Route::post('/sign-out', 'UsersController@sign_out');
//Route::post('/user/add-data', 'UsersController@load')->middleware('admin');

Route::post('/role/add', 'RolesController@add')->middleware('login', 'admin');
Route::post('/role/get/{id?}', 'RolesController@get')->middleware( 'login', 'admin');
Route::post('/role/delete/{id}', 'RolesController@delete')->middleware( 'login', 'admin');
Route::post('/role/update/{id}', 'RolesController@update')->middleware( 'login', 'admin');

Route::post('/product/add', 'ProductsController@add')->middleware('login', 'admin');
Route::post('/product/get/{id?}', 'ProductsController@get')->middleware('login');
Route::post('/product/delete/{id}', 'ProductsController@delete')->middleware('login');
Route::post('/product/update/{id}', 'ProductsController@update')->middleware('login');
Route::post('/product/edit/inventory/{id}', 'ProductsController@edit_inventory')->middleware('login');
Route::post('/product/edit/reservation_inventory/{id}', 'ProductsController@edit_reservation_inventory')->middleware('login');
Route::post('/product/get_image/{id}', 'ProductsController@get_image')->middleware('login');

Route::post('/product-category/add', 'ProductCategoriesController@add')->middleware('login', 'admin');
Route::post('/product-category/get/{id?}', 'ProductCategoriesController@get')->middleware('login');

Route::post('/accountant/add', 'AccountantsController@add')->middleware('login', 'admin');
Route::post('/accountant/get/{id?}', 'AccountantsController@get')->middleware('login');

Route::post('/order/add', 'OrdersController@add')->middleware('login');
Route::post('/order/get/{id?}', 'OrdersController@get')->middleware('login');
Route::post('/order/get/filter', 'OrdersController@filter')->middleware('login');
Route::post('/order/location/add', 'OrdersController@add_location')->middleware('login');

Route::post('/marketer/add', 'MarketersController@add')->middleware('login', 'admin');
Route::post('/marketer/get/{id?}', 'MarketersController@get')->middleware('login');

Route::post('/customer/add', 'CustomersController@add')->middleware('login');
Route::post('/customer/get/{id?}', 'CustomersController@get')->middleware('login');
Route::post('/customer/location/add', 'CustomersController@add_location')->middleware('login');
//Route::post('/customer/add-data', 'CustomersController@load')->middleware('login', 'admin');

Route::post('/driver/add', 'DriversController@add')->middleware('login', 'admin');
Route::post('/driver/get/{id?}', 'DriversController@get')->middleware( 'login', 'admin');
Route::post('/driver/delete/{id}', 'DriversController@delete')->middleware( 'login', 'admin');
Route::post('/driver/update/{id}', 'DriversController@update')->middleware( 'login', 'admin');

Route::post('/test', 'TestController@test');