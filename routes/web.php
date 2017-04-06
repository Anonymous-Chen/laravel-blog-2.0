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



// Blog pages
Route::get('/', function () {
    return redirect('blog');
});
Route::get('blog', 'BlogController@ind');
Route::get('blog/{slug}', 'BlogController@showPost');

$router->get('contact', 'ContactController@showForm');
Route::post('contact', 'ContactController@sendContactInfo');

Route::get('te', 'Controller@index');

Route::get('admin1', 'Controller@admin1');


//Admin area
Route::get('admin', function () {
    return redirect('login');
});
Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::any('admin/post/{id}/update', ['uses'=>'PostController@update']);
    Route::any('admin/post/store', ['uses'=>'PostController@store']);
    Route::any('admin/post/{id}/destroy', ['uses'=>'PostController@destroy']);
    Route::resource('admin/post', 'PostController', ['except' => 'show']);
    Route::resource('admin/tag', 'TagController', ['except' => 'show']);
    Route::any('admin/upload', ['uses'=>'UploadController@ind']);
    Route::post('admin/upload/file', 'UploadController@uploadFile');
    Route::delete('admin/upload/file', 'UploadController@deleteFile');
    Route::post('admin/upload/folder', 'UploadController@createFolder');
    Route::delete('admin/upload/folder', 'UploadController@deleteFolder');
});


// Logging in and out
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');
Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('logout', array('before' => 'auth', function()
{
    Auth::logout();
    return Redirect::to('admin/post');
}));
