<?php
define('MAX_BID_PER_SALE', 4);

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
/*
 * Routes de base
 */

// Affiche l'accueil du site
Route::get('/', ['as' => 'index', 'uses' => 'PagesController@index']);

// Affiche les enchères
Route::get('items', ['as' => 'items', 'uses' => 'ItemsController@index']);
Route::get('items/sort/by/{type}/{sort}', ['as' => 'items_sort', 'uses' => 'ItemsController@index']);

// Affiche l'enchère n°$id
Route::get('item/{id}', ['as' => 'item', 'uses' => 'ItemsController@see'])->where('id', '[0-9]+');

// Traitement pour enchérir sur une annonce
Route::post('item/{id}', ['as' => 'bid', 'middleware' => 'auth', 'uses' => 'BidsController@add']);

Route::group(['prefix' => 'sale', 'as' => 'sell::'], function() {
    // Affiche le formulaire de mise en vente
    Route::get('/', ['as' => 'index', 'middleware' => 'auth', 'uses' => 'SalesController@index']);
    // Ajout d'une nouvelle vente dans la bdd
    Route::post('add', ['as' => 'add', 'middleware' => 'auth', 'uses' => 'ItemsController@add']);
});

// Permet de rediriger un utilisateur vers une page (genre sur la page des ventes avec les différentes options de tri)
Route::get('redirect_to', ['as' => 'redirect_to', function() {
    return redirect(Input::get('url', url('/')));
}]);

// ça on verra un autre jour
Route::get('profile', ['as' => 'profile', 'middleware' => 'auth', function() {
    return view('profile');
}]);

/*
 * Authentification
 */

// Affiche le formulaire de connexion
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
// Traitement pour la connexion
Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
// Déconnexion
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

// Affiche le formulaire d'inscription
Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
// Traitement pour l'inscription
Route::post('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

/*
 * Administration
 */

Route::group(['prefix' => 'admin', 'as' => 'admin::', 'middleware' => 'admin'], function() {

    // Affiche la page d'accueil de l'administration
    Route::get('/', ['as' => 'index', 'uses' => 'AdminController@index']);

    // Epuration de la bdd
    Route::post('refine', ['as' => 'refine', 'uses' => 'AdminController@refine']);
});

Route::controllers([
   'password' => 'Auth\PasswordController',
]);
