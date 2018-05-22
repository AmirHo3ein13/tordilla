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
Route::post('/user/remove/{id}', 'UsersController@remove')->middleware('admin');
Route::post('/user/update', 'UsersController@update')->middleware('admin');
Route::post('/sign-out', 'UsersController@sign_out');
Route::post('/user/get/{id?}', 'UsersController@get')->middleware('admin');
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
Route::get('/product/image_path/{folder}/{filename}', 'ProductsController@get_image_path');

Route::post('/product-category/add', 'ProductCategoriesController@add')->middleware('login', 'admin');
Route::post('/product-category/get/{id?}', 'ProductCategoriesController@get')->middleware('login');
Route::post('/product-category/update/{id}', 'ProductCategoriesController@update')->middleware('login', 'admin');
Route::post('/product-category/delete/{id}', 'ProductCategoriesController@delete')->middleware('login', 'admin');

Route::post('/accountant/add', 'AccountantsController@add')->middleware('login', 'admin');
Route::post('/accountant/get/{id?}', 'AccountantsController@get')->middleware('login');
Route::post('/accountant/update/{id}', 'AccountantsController@update')->middleware('login', 'admin');
Route::post('/accountant/delete/{id}', 'AccountantsController@delete')->middleware('login', 'admin');

Route::post('/order/add', 'OrdersController@add')->middleware('login');
Route::post('/order/get/{id?}', 'OrdersController@get')->middleware('login');
Route::post('/order/update/{id}', 'OrdersController@update')->middleware('login');
Route::post('/order/delete/{id}', 'OrdersController@delete')->middleware('login');
//Route::post('/order/edit/step/{id}', 'OrdersController@change_step')->middleware('login');
Route::post('/order/filter', 'OrdersController@filter');
Route::post('/order/location/add', 'OrdersController@add_location')->middleware('login');

Route::post('/marketer/add', 'MarketersController@add')->middleware('login', 'admin');
Route::post('/marketer/get/{id?}', 'MarketersController@get')->middleware('login');
Route::post('/marketer/update/{id}', 'MarketersController@update')->middleware('login', 'admin');
Route::post('/marketer/delete/{id}', 'MarketersController@delete')->middleware('login', 'admin');

Route::post('/customer/add', 'CustomersController@add')->middleware('login');
Route::post('/customer/get/{id?}', 'CustomersController@get')->middleware('login');
Route::post('/customer/location/add', 'CustomersController@add_location')->middleware('login');
Route::post('/customer/update/{id}', 'CustomersController@update')->middleware('login');
Route::post('/customer/delete/{id}', 'CustomersController@delete')->middleware('login');
Route::post('/customer/search', 'CustomersController@search')->middleware('login');
Route::post('/customer/map', 'CustomersController@search_on_map')->middleware('login');
Route::post('/customer/search_phone', 'CustomersController@search_by_phone')->middleware('login');
//Route::post('/customer/add-data', 'CustomersController@load')->middleware('login', 'admin');

Route::post('/driver/add', 'DriversController@add')->middleware('login', 'admin');
Route::post('/driver/get/{id?}', 'DriversController@get')->middleware( 'login', 'admin');
Route::post('/driver/delete/{id}', 'DriversController@delete')->middleware( 'login', 'admin');
Route::post('/driver/update/{id}', 'DriversController@update')->middleware( 'login', 'admin');

Route::post('/test', 'TestController@test');

Route::post('/city/add', 'CitiesController@store')->middleware('login', 'admin');
Route::post('/city/get/{id?}', 'CitiesController@index')->middleware('login');
Route::post('/city/update/{id}', 'CitiesController@update')->middleware('login', 'admin');
Route::post('/city/delete/{id}', 'CitiesController@destroy')->middleware('login', 'admin');
