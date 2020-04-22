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

Route::get('/', 'HomeController@index')->name('main');
Route::get('/post/{slug}', 'HomeController@show')->name('post.show');
Route::get('/tag/{slug}', 'HomeController@tag')->name('tag.show');
Route::get('/category/{slug}', 'HomeController@category')->name('category.show');
Route::post('/subscribe', 'SubsController@subscribe');
Route::get('/verify/{token}', 'SubsController@verify');

Route::group(['middleware' => 'guest'], function() {
	Route::get('/register', 'AuthController@registerForm');
	Route::post('/register', 'AuthController@register');

	Route::get('/login', 'AuthController@loginForm')->name('login');
	Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => 'auth'], function() {
	Route::get('/profile', 'ProfileController@index');
	Route::post('/profile', 'ProfileController@update');

	Route::post('/comment', 'CommentsController@store');

	Route::get('/logout', 'AuthController@logout');
});

Route::group([
	'prefix' => 'admin', 
	'namespace' => 'Admin', 
	'middleware' => 'admin'
], function() {
	Route::get('/', 'DashboardController@index');
	Route::resource('/categories', 'CategoriesController');
	Route::resource('/tags', 'TagsController');
	Route::resource('/users', 'UsersController');
	Route::resource('/posts', 'PostsController');
	Route::resource('/subscribers', 'SubsController');
	Route::get('/comments', 'CommentsController@index')->name('comments.index');
	Route::get('/comments/status/{id}', 'CommentsController@status')->name('comments.status');
	Route::delete('/comments/{id}/destroy', 'CommentsController@destroy')->name('comments.destroy');
	Route::get('/users/toggleStatus/{id}', 'UsersController@toggleStatus')->name('users.status');
	Route::get('/users/toggleAdmin/{id}', 'UsersController@toggleAdmin')->name('users.admin');
	Route::get('/mailings/write', 'MailingsController@write')->name('mailings.write');
	Route::post('/mailings/send', 'MailingsController@send')->name('mailings.send');
});
