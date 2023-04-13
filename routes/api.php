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

// SCHOOL CLASSES
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'admin/school-class',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/classes', 'SchoolClassController@classes');
        Route::post('/create', 'SchoolClassController@register_class');
        Route::put('/update', 'SchoolClassController@update');
    }
);

/////// SCHOOL TERM SESSION CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'school-term',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/create', 'SchoolTermSessionController@create');
        Route::get('/terms', 'SchoolTermSessionController@terms');
        Route::put('/update', 'SchoolTermSessionController@update');
        Route::post('/activate', 'SchoolTermSessionController@activate_term');
    }
);

/////// SCHOOL SESSION CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'school-session',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get(
            '/yealy-session',
            'SchoolSessionController@school_session_years'
        );

        Route::post('/create', 'SchoolSessionController@create');

        Route::get(
            '/school-sessions',
            'SchoolSessionController@school_sessions'
        );

        Route::put(
            '/activate-session',
            'SchoolSessionController@activate_session'
        );

        Route::put('/update', 'SchoolSessionController@update');
    }
);

// Role (Control and Manage Role)
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'role',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/create', 'RoleController@create');
        Route::get('/roles', 'RoleController@roles');
        Route::put('/role', 'RoleController@update');
        Route::delete('/delete', 'RoleController@delete');
    }
);

// Staff (Control and Manage Staff)
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'staff',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/create', 'StaffController@create');
        Route::get('/staffs', 'StaffController@staffs');
    }
);

// routes action for users Auth
Route::group(
    [
        'middleware' => ['api', 'login.logger'],
        'prefix' => 'auth',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/login', 'AuthController@login');
        Route::post('/user-register', 'AuthController@user_register');
        //  Route::post('/register', 'AuthController@register');
    }
);

// ************************************

// USERS SECTION

// ****************************************

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
