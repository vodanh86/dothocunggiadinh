<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('business', 'BusinessController@find');
Route::get('branch', 'BranchController@find');

Route::get('product-group', 'ProductGroupController@find');
Route::get('product-group/all', 'ProductGroupController@getAll');
Route::get('product-group/get-all', 'ProductGroupController@getAllWithLimit');
Route::get('product-group/get-by-id', 'ProductGroupController@getById');

Route::get('category', 'CategoryController@find');
Route::get('category/get-by-product-group', 'CategoryController@findByProductGroup');
Route::get('category/all', 'CategoryController@getAll');
Route::get('category/get-by-id', 'CategoryController@getById');

Route::get('product', 'ProductController@find');
Route::get('product/all', 'ProductController@getAll');
Route::get('product/get-by-id', 'ProductController@getById');
Route::get('product/high-light-product', 'ProductController@listHighLight');
Route::get('product/get-by-product-group/{id}', 'ProductController@getByProductGroup');
Route::get('product/get-related-product/{id}', 'ProductController@relatedProduct');

Route::get('events/coming-soon', 'EventController@listComingSoonEvents');
Route::get('events/get-by-id/{id}', 'EventController@find');

Route::get('news/list-latest-news', 'NewsController@latestNews');
Route::get('news/get-by-id/{id}', 'NewsController@find');

Route::get('system-information/about-us', 'SystemInformationController@aboutUs');
Route::get('system-information/contact', 'SystemInformationController@contactInformation');



