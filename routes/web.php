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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// For User
Route::get('user/{id}', 'UserController@show')->name('user.show');
Route::get('/user/{id}/edit', 'UserController@edit')->name('user.edit');
Route::patch('/user/{id}/update', 'UserController@update')->name('user.update');

Route::get('/user/{id}/password/edit', 'UserController@editPassword')->name('user.editPassword');
Route::patch('/user/password/{id}/update', 'UserController@updatePassword')->name('user.updatePassword');

// For Admin user (using Gate (app/Providers/AppServiceProvider.php))
Route::group(['middleware' => 'can:isAdmin'], function () {
    Route::get('admin', 'AdminController@index')->name('admin.home');

    Route::get('admin/users', 'AdminController@showUsers')->name('admin.showUsers');
    Route::patch('admin/users/{id}/restore', 'AdminController@activateUser')->name('admin.activateUser');
    Route::delete('admin/users/{id}/delete', 'AdminController@deactivateUser')->name('admin.deactivateUser');

    Route::get('admin/posts', 'AdminController@showPosts')->name('admin.showPosts');
    Route::patch('admin/posts/{id}/unhide', 'AdminController@unhidePost')->name('admin.unhidePost');
    Route::delete('admin/posts/{id}/hide', 'AdminController@hidePost')->name('admin.hidePost');

    Route::get('admin/categories', 'CategoryController@index')->name('admin.category');
    Route::post('admin/categories/store', 'CategoryController@store')->name('admin.categoryStore');
    Route::patch('admin/categories/{id}/update', 'CategoryController@update')->name('admin.categoryUpdate');
    Route::delete('admin/categories/{id}/delete', 'CategoryController@destroy')->name('admin.categoryDestroy');
});

// Follow users
Route::get('/user/{id}/follow', 'UserController@follow')->name('user.follow');

// For Post
Route::get('/post/create', 'PostController@create')->name('post.create');
Route::post('/post/store', 'PostController@store')->name('post.store');
Route::get('/post/{id}', 'PostController@show')->name('post.show');
Route::get('/posts', 'PostController@list')->name('post.posts');
Route::get('/posts/{id}/edit', 'PostController@edit')->name('post.edit');
Route::patch('/posts/{id}/update', 'PostController@update')->name('post.update');
Route::delete('/posts/{id}/delete', 'PostController@destroy')->name('post.destroy');

Route::get('posts/category/{id}', 'PostController@categoryPost')->name('post.categoryPost');

// Like on posts
Route::get('/posts/{id}/like', 'PostController@like')->name('post.like');

// For Comment
Route::post('/post/{id}/comment', 'CommentController@store')->name('comment.store');
Route::patch('/post/{id}/comment/update', 'CommentController@update')->name('comment.update');
Route::delete('/comment/{id}/comment/delete', 'CommentController@destroy')->name('comment.destroy');
