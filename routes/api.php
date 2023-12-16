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
Route::get('product-group/get-by-id', 'ProductGroupController@getById');
Route::get('category', 'CategoryController@find');
Route::get('category/get-by-product-group', 'CategoryController@findByProductGroup');
Route::get('category/all', 'CategoryController@getAll');
Route::get('category/get-by-id', 'CategoryController@getById');
Route::get('product', 'ProductController@find');
Route::get('product/all', 'ProductController@getAll');
Route::get('product/get-by-id', 'ProductController@getById');
Route::get('product/getProductDetail', 'ProductController@getProductDetail');

