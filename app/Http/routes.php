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

	// user settings change name
	Route::post('user/settings/name', ['as' => 'user.settings.name', 'uses' => 'UserController@changeName']);

	// user settings change password
	Route::post('user/settings/password', ['as' => 'user.settings.password', 'uses' => 'UserController@changePass']);

	// user settings change theme
	Route::post('user/settings/theme', ['as' => 'user.settings.theme', 'uses' => 'UserController@changeTheme']);

	// user account delete
	Route::delete('user', ['as' => 'user.settings.delete', 'uses' => 'UserController@deleteAccount']);

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

// pass theme list and user info into theme change page
View::composer('user._changeTheme', function($view) {
	// get theme list
	$themes = App\Theme::orderBy('name')->get();

	$view->with('themes', $themes);
});

// pass theme list and user info into summary page
View::composer('user._summary', function($view) {
	// get user info
	$user = App\User::find(Auth::user()->id);

	// calculate total income and total expense
    $incomeTotal = App\User::find(Auth::user()->id)->transactions->where('type', 'DEPOSIT')->sum('amount');
    $expenseTotal = App\User::find(Auth::user()->id)->transactions->where('type', 'WITHDRAWAL')->sum('amount');

	$view->with('user', $user)->with('incomeTotal', $incomeTotal)->with('expenseTotal', $expenseTotal);
});