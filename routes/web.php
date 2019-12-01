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

/*Route::get('/', function () {
    // return view('welcome');
    return view('blog.index');
});*/

Route::get('/', [
	'uses' => 'BlogController@index',
	'as' => 'blog'
]);

/*Route::get('/blog/show', function () {
	return view('blog.show');
});*/

Route::get('/blog/{post}', [
	'uses' => 'BlogController@show',
	'as' => 'blog.show'
]);

Route::post('/blog/{post}/comments', [
	'uses' => 'CommentsController@store',
	'as' => 'blog.comments'
]);

Route::get('/category/{category}', [
	'uses' => 'BlogController@category',
	'as' => 'category'
]);

Route::get('/author/{author}', [
	'uses' => 'BlogController@author',
	'as' => 'author'
]);

Route::get('/tag/{tag}', [
	'uses' => 'BlogController@tag',
	'as' => 'tag'
]);


Auth::routes();


Route::get('/home', 'Backend\HomeController@index')->name('home');
Route::get('/edit-account', 'Backend\HomeController@edit')->name('edit-account');
Route::put('/edit-account', 'Backend\HomeController@update')->name('update-account');

Route::put('/backend/blog/restore/{blog}', [
	'uses' => 'Backend\BlogController@restore',
	'as'   => 'backend.blog.restore'
]);
Route::delete('/backend/blog/force-destroy/{blog}', [
	'uses' => 'Backend\BlogController@forceDestroy',
	'as'   => 'backend.blog.force-destroy'
]);
Route::resource('/backend/blog', 'Backend\BlogController', [
	'as' => 'backend'
]);

Route::resource('/backend/categories', 'Backend\CategoriesController', [
	'as' => 'backend'
]);

Route::resource('/backend/tags', 'Backend\TagsController', [
	'as' => 'backend'
]);

Route::get('/backend/users/confirm/{user}', [
    'uses' => 'Backend\UsersController@confirm',
    'as' => 'backend.users.confirm'
]);
Route::resource('/backend/users', 'Backend\UsersController', [
	'as' => 'backend'
]);

/* Route::resource('/backend/blog', 'Backend\BlogController', ['names' => [
    'index' => 'backend.blog.index',
    'create' => 'backend.blog.create',
    'edit' => 'backend.blog.edit',
]]); */

Route::get('/export_excel', 'ExportExcelController@index');

Route::get('/export_excel/excel', 'ExportExcelController@excel')->name('export_excel.excel');
Route::get('/export_excel/export', 'ExportExcelController@export')->name('export_excel.export');