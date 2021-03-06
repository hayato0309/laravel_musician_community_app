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
// Auth::routes(['verify' => true]);
// Email認証機能を一時停止中（当プロジェクト確認の際に手間なので）
// RegisterControllerに記載のイベントもコメントアウト中
// 下のRoute::groupからも'verified'を削除

// Route::group(['middleware' => ['auth', 'verified']], function () {
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // For User
    Route::get('/user/{id}', 'UserController@show')->name('user.show');
    Route::get('/user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('/user/{id}/update', 'UserController@update')->name('user.update');

    Route::get('/user/{id}/password/edit', 'UserController@editPassword')->name('user.editPassword');
    Route::patch('/user/password/{id}/update', 'UserController@updatePassword')->name('user.updatePassword');


    // For Admin user (using Gate (app/Providers/AppServiceProvider.php))
    Route::group(['middleware' => 'can:isAdmin'], function () {
        Route::get('/admin', 'AdminController@index')->name('admin.home');

        // Notifications
        Route::get('/admin/notifications', 'AdminController@showNotifications')->name('admin.notifications');

        // Inquiries
        Route::get('/admin/inquiries', 'AdminController@showInquiries')->name('admin.inquiries');
        Route::get('/admin/inquiries/{id}/solve', 'AdminController@solveInquiry')->name('admin.solveInquiry');
        Route::get('/admin/inquiries/{id}/unsolve', 'AdminController@unsolveInquiry')->name('admin.unsolveInquiry');

        // Users
        Route::get('/admin/users', 'AdminController@showUsers')->name('admin.users');
        Route::patch('/admin/users/{id}/restore', 'AdminController@activateUser')->name('admin.activateUser');
        Route::delete('/admin/users/{id}/delete', 'AdminController@deactivateUser')->name('admin.deactivateUser');

        Route::post('/admin/users/{id}/roles/update', 'AdminController@updateRoles')->name('admin.updateRoles');

        // Posts
        Route::get('/admin/posts', 'AdminController@showPosts')->name('admin.posts');
        Route::patch('/admin/posts/{id}/unhide', 'AdminController@unhidePost')->name('admin.unhidePost');
        Route::delete('/admin/posts/{id}/hide', 'AdminController@hidePost')->name('admin.hidePost');

        // Post types
        Route::get('/admin/posts_types', 'PostTypeController@index')->name('admin.postTypes');
        Route::post('/admin/posts_types/store', 'PostTypeController@store')->name('admin.postTypeStore');
        Route::patch('/admin/post_type/{id}/update', 'PostTypeController@update')->name('admin.postTypeUpdate');
        Route::delete('/admin/post_types/{id}/delete', 'PostTypeController@destroy')->name('admin.postTypeDestroy');

        // Categories
        Route::get('/admin/categories', 'CategoryController@index')->name('admin.categories');
        Route::post('/admin/categories/store', 'CategoryController@store')->name('admin.categoryStore');
        Route::patch('/admin/categories/{id}/update', 'CategoryController@update')->name('admin.categoryUpdate');
        Route::delete('/admin/categories/{id}/delete', 'CategoryController@destroy')->name('admin.categoryDestroy');
    });

    // Follow users
    Route::get('/user/{id}/follow', 'UserController@follow')->name('user.follow');


    // For Post
    Route::get('/post/create', 'PostController@create')->name('post.create');
    Route::post('/post/store', 'PostController@store')->name('post.store');
    Route::get('/posts/{id}', 'PostController@show')->name('post.show');
    Route::get('/posts', 'PostController@list')->name('post.list');
    Route::get('/posts/{id}/edit', 'PostController@edit')->name('post.edit');
    Route::patch('/posts/{id}/update', 'PostController@update')->name('post.update');
    Route::delete('/posts/{id}/delete', 'PostController@destroy')->name('post.destroy');

    Route::get('/category/{id}/posts', 'PostController@categoryPost')->name('post.categoryPost');

    Route::get('/favorite_posts', 'PostController@favoritePost')->name('post.favoritePost');
    Route::delete('/favorite_posts/{id}/undo_like', 'PostController@like')->name('post.undoLike');


    // Like on posts
    Route::get('/posts/{id}/like', 'PostController@like')->name('post.like');


    // For Comment
    Route::post('/posts/{id}/comment/store', 'CommentController@store')->name('comment.store');
    Route::patch('/posts/{id}/comment/update', 'CommentController@update')->name('comment.update');
    Route::delete('/comment/{id}/comment/delete', 'CommentController@destroy')->name('comment.destroy');
    Route::post('/posts/{post_id}/comments/{comment_id}/store', 'CommentController@replyStore')->name('comment.replyStore');

    Route::post('/ensembles/{ensemble_id}/comments/store', 'CommentController@storeForEnsemble')->name('comment.storeForEnsemble');
    Route::post('/ensembles/{ensemble_id}/comments/{comment_id}/store', 'CommentController@replyStoreForEnsemble')->name('comment.replyStoreForEnsemble');


    // For Notification
    Route::get('/notifications', 'NotificationController@index')->name('notification.notifications');


    // For Inquiries (contact us function)
    Route::get('/contact_us', 'InquiryController@create')->name('inquiry.create');
    Route::post('contact_us/store', 'InquiryController@store')->name('inquiry.store');


    // For Ensemble
    Route::get('/ensembles', 'EnsembleController@home')->name('ensemble.home');
    Route::get('/ensemble/create', 'EnsembleController@create')->name('ensemble.create');
    Route::post('/ensemble/store', 'EnsembleController@store')->name('ensemble.store');

    Route::get('/ensembles/my_ensembles', 'EnsembleController@myEnsembles')->name('ensemble.myEnsembles');

    Route::get('/ensembles/{id}', 'EnsembleController@show')->name('ensemble.show');
    Route::get('/ensembles/{id}/edit', 'EnsembleController@edit')->name('ensemble.edit');
    Route::patch('/ensembles/{id}/update', 'EnsembleController@update')->name('ensemble.update');
    Route::delete('/ensembles/{id}/delete', 'EnsembleController@destroy')->name('ensemble.destroy');
    Route::patch('/ensembles/{id}/reopen', 'EnsembleController@reopen')->name('ensemble.reopen');


    // For Ensemble Application
    Route::post('/ensembles/{id}/ensemble_application/store', 'EnsembleApplicationController@store')->name('ensembleApplication.store');


    // For Chat
    Route::get('/chat', 'ChatController@index')->name('chat');
    Route::post('/chat/store', 'ChatController@store')->name('chat.store');
    Route::get('/result/ajax', 'ChatController@getMessages')->name('chat.getMessages');
});
