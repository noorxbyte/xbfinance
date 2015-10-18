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

// Protected routes...
Route::group(['middleware' => 'auth'], function()
{
	// redirect /home to account list
	Route::get('/home', ['as' => 'home', 'uses' => function()
	{
		return redirect()->route('accounts.index');
	}]);

	// redirect / to account list
	Route::get('/', function() {
		return redirect()->route('accounts.index');
	});
	// accounts routes
	Route::resource('accounts', 'AccountsController');

	// transactions routes
	Route::resource('transactions', 'TransactionsController');

	// transfers routes
	Route::resource('transfers', 'TransfersController', ['except' => ['show']]);

	// categories routes
	Route::resource('categories', 'CategoriesController');

	// payees routes
	Route::resource('payees', 'PayeesController');

	// user settings main page
	Route::get('user/settings', ['as' => 'user.settings', 'uses' => 'UserController@index']);

	// user settings change theme
	Route::post('user/settings/theme', ['as' => 'user.settings.theme', 'uses' => 'UserController@changeTheme']);

});

// login routes....
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');

// logout route...
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

// register route...
Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', ['as' => 'reset.getEmail', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post('password/email', ['as' => 'reset.postEmail', 'uses' => 'Auth\PasswordController@postEmail']);

// Password reset routes...
Route::get('password/reset/{token}', ['as' => 'reset.getReset', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset', ['as' => 'reset.postReset', 'uses' => 'Auth\PasswordController@postReset']);


/************************************** --- **************************************/


// pass accounts data into navbar
View::composer('_navbar', function($view) {
	if (isset(Auth::user()->id))
	{
		$nav_accounts = App\User::find(Auth::user()->id)->accounts;
		$username = Auth::user()->name;
		$view->with('nav_accounts', $nav_accounts)->with('username', $username);
	}
});

// pass theme into master page
View::composer('master', function($view) {
	if (isset(Auth::user()->id))
	{
		$usertheme = Auth::user()->theme;
		$view->with('usertheme', $usertheme);
	}
});

// pass theme list into settings page
View::composer('user.index', function($view) {
	$themes = App\Theme::orderBy('name')->get();
	$view->with('themes', $themes);
});