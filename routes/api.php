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

Route::get('/hello', function (Request $request) {
    return 'hello';
});

Route::group(['namespace' => 'App\Http\Controllers'], function ($router) {
    Route::get('/database-test', 'DatabseTestController@index');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Staff (Control and Manage Staff)
Route::group(
    [
        'middleware' => 'jwt.verify',
        'prefix' => 'staff',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/create', 'StaffController@create');
    }
);

// routes action for users Auth
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
    }
);

// routes action for users
Route::group(
    [
        'middleware' => 'jwt.verify',
        'prefix' => 'user',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/dashboard', 'UserController@dashboard');
    }
);

// routes action for users
Route::group(
    [
        'middleware' => ['jwt.verify'],
        'prefix' => 'admin',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/school-class/all', 'SchoolClassController@all_class');
        Route::post(
            '/school-class/create',
            'SchoolClassController@register_class'
        );
    }
);
