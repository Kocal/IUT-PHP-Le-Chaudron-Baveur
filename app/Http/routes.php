<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/**
 * Routes de base
 */
Route::get('/', ['as' => 'index', function() {
    return view('index');
}]);

Route::get('/items', ['as' => 'items', function() {
    return view('items');
}]);

Route::get('/item/{id}', ['as' => 'item', function($id) {
    return 'hey' . $id;
}])->where('id', '[0-9]+');

Route::group(['prefix' => 'sale', 'as' => 'sell::'], function() {
    Route::get('/', ['as' => 'index', 'middleware' => 'auth', 'uses' => 'SaleController@index']);
    Route::post('/add', ['as' => 'add', 'middleware' => 'auth', 'uses' => 'ItemsController@add']);
});

Route::get('/profile', ['as' => 'profile', 'middleware' => 'auth', function() {
    return view('profile');
}]);

/*
 * Authentification
 */
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

/*
 * Administration
 */
Route::group(['prefix' => 'admin', 'as' => 'admin::', 'middleware' => 'admin'], function() {
   Route::get('/', ['as' => 'index', function() {
       return view('admin.index');
   }]);

    Route::post('purge', ['as' => 'purge', 'uses' => 'AdminController@purgeAds']);
});

Route::controllers([
   'password' => 'Auth\PasswordController',
]);
