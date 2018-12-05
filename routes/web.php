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

Route::get('/category/{category}', [
	'uses' => 'BlogController@category',
	'as' => 'category'
]);

Route::get('/author/{author}', [
	'uses' => 'BlogController@author',
	'as' => 'author'
]);


Auth::routes();


Route::get('/home', 'Backend\HomeController@index')->name('home');

Route::resource('/backend/blog', 'Backend\BlogController', [
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