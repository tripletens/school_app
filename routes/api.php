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
        'prefix' => 'admin',
        'namespace' => 'App\Http\Controllers\Admin',
    ],
    function ($router) {
        Route::get('/dashboard', 'DashboardController@dashboard');
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

// ADMIN SECTIONS ///////
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'admin',
        'namespace' => 'App\Http\Controllers\Admin',
    ],
    function ($router) {
        ////// SCHOOL CLASSES ////////
        Route::group(
            [
                'prefix' => 'school-class',
            ],
            function ($router) {
                Route::get('/classes', 'SchoolClassController@classes');
                Route::get('/index', 'SchoolClassController@index');
                Route::post('/create', 'SchoolClassController@create');
                Route::post('/update', 'SchoolClassController@update');
                Route::post('/activate', 'SchoolClassController@activate');
                Route::post('/deactivate', 'SchoolClassController@deactivate');
            }
        );

        // EMAIL TEMPLATE SETTINGS ///////
        Route::group(
            [
                'prefix' => 'email-template',
            ],
            function ($router) {
                Route::post('/create', 'EmailTemplateController@create');
                Route::post('/update', 'EmailTemplateController@update');
                Route::post(
                    '/active',
                    'EmailTemplateController@active_provider'
                );
                Route::post(
                    '/deactivate',
                    'EmailTemplateController@deactivate'
                );
                Route::get('/index', 'EmailTemplateController@index');
            }
        );

        // SMS SERVICES SETTINGS ///////
        Route::group(
            [
                'prefix' => 'sms-services',
            ],
            function ($router) {
                Route::post('/create', 'SmsServicesController@create');
                Route::post('/update', 'SmsServicesController@update');
                Route::post('/active', 'SmsServicesController@active_provider');
                Route::post('/deactivate', 'SmsServicesController@deactivate');
                Route::get('/index', 'SmsServicesController@index');
                Route::get('/termii', 'SmsServicesController@send_sms');
                Route::get(
                    '/balance/{provider}',
                    'SmsServicesController@balance'
                );
            }
        );

        ////// CLASS LEVEL SETTINGS ///////
        Route::group(
            [
                'prefix' => 'class-level',
            ],
            function ($router) {
                Route::post('/create', 'ClassLevelController@create');
                Route::post('/update', 'ClassLevelController@update');
                Route::post('/activate', 'ClassLevelController@activate');
                Route::post('/deactivate', 'ClassLevelController@deactivate');
                Route::get('/index', 'ClassLevelController@index');
                Route::get(
                    '/active-levels',
                    'ClassLevelController@active_levels'
                );
            }
        );

        ////// CLASS CATEGORY SETTINGS ///////
        Route::group(
            [
                'prefix' => 'class-category',
            ],
            function ($router) {
                Route::post('/create', 'ClassCategoryController@create');
                Route::post('/update', 'ClassCategoryController@update');
                Route::post('/activate', 'ClassCategoryController@activate');
                Route::post(
                    '/deactivate',
                    'ClassCategoryController@deactivate'
                );
                Route::get('/index', 'ClassCategoryController@index');

                Route::get(
                    '/active-categorys',
                    'ClassCategoryController@active_categorys'
                );
            }
        );

        // SERVICES PROVIDERS SETTINGS ///////
        Route::group(
            [
                'prefix' => 'service-providers',
            ],
            function ($router) {
                Route::post('/create', 'ServiceProviderController@create');
                Route::post('/update', 'ServiceProviderController@update');
                Route::post('/activate', 'ServiceProviderController@activate');
                Route::post(
                    '/deactivate',
                    'ServiceProviderController@deactivate'
                );
                Route::get('/index', 'ServiceProviderController@index');
            }
        );

        // SCHOOL SETTINGS ///////
        Route::group(
            [
                'prefix' => 'school-settings',
                'middleware' => 'cors',
            ],
            function ($router) {
                Route::post('/create', 'SchoolSettingsController@create');
                Route::post('/update', 'SchoolSettingsController@update');
                Route::get('/index', 'SchoolSettingsController@index');
                Route::post(
                    '/personification',
                    'SchoolSettingsController@personification'
                );
            }
        );

        // SMTP Settings ///////
        Route::group(
            [
                'prefix' => 'smtp-settings',
            ],
            function ($router) {
                Route::post('/create', 'SmtpSettingsController@create');
                Route::post('/update', 'SmtpSettingsController@update');
                Route::get('/index', 'SmtpSettingsController@index');
                Route::put('/toggle', 'SmtpSettingsController@toggle');

                Route::post(
                    '/test-mail',
                    'SmtpSettingsController@send_mail_test'
                );

                Route::post(
                    '/send-smtp-test-mail',
                    'SmtpSettingsController@send_smtp_mail_test'
                );
            }
        );

        // Cloudinary Settings ///////
        Route::group(
            [
                'prefix' => 'cloudinary-settings',
            ],
            function ($router) {
                Route::post('/create', 'CloudinarySettingsController@create');
                Route::post('/update', 'CloudinarySettingsController@update');
                Route::get('/index', 'CloudinarySettingsController@index');
                Route::post('/toggle', 'CloudinarySettingsController@toggle');
                Route::post('/testing', 'CloudinarySettingsController@testing');
            }
        );

        // ROLE MANAGEMENT ///////
        Route::group(
            [
                'prefix' => 'role',
            ],
            function ($router) {
                Route::post('/create', 'RoleController@create');
                Route::get('/index', 'RoleController@index');
                Route::put('/role', 'RoleController@update');
                Route::delete('/delete', 'RoleController@delete');
                Route::get('/slug/{slug}', 'RoleController@slug');
            }
        );

        // STAFF ///////
        Route::group(
            [
                'prefix' => 'staff',
            ],
            function ($router) {
                Route::post('/create', 'UserBioController@create');
                Route::get('/index', 'UserBioController@index');
                Route::get('/user/{role}', 'UserBioController@user');
                Route::post('/staff', 'UserBioController@staff');
                Route::post('/create-staff', 'UserBioController@create_staff');
            }
        );

        // STAFF ///////
        Route::group(
            [
                'prefix' => 'marquee',
            ],
            function ($router) {
                Route::get('/index', 'MarqueeController@index');
                Route::post('/activate', 'MarqueeController@activate');
                Route::post('/deactivate', 'MarqueeController@deactivate');

                Route::post(
                    '/deactivate-slug',
                    'MarqueeController@deactivate_slug'
                );
                Route::post(
                    '/activate-slug',
                    'MarqueeController@activate_slug'
                );
            }
        );

        /////// Subject CRUD
        Route::group(
            [
                'prefix' => 'subject',
            ],
            function ($router) {
                Route::post('/add', 'SubjectController@store');
                Route::post('/update', 'SubjectController@update');
                Route::post('/delete', 'SubjectController@destroy');
                Route::get('/all', 'SubjectController@index');
                Route::get(
                    '/fetch-one-subject',
                    'SubjectController@fetch_one_subject'
                );
            }
        );
    }
);

/////// User CRUD
Route::group(
    [
        'prefix' => 'admin/user',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/upload-user', 'UserController@upload_user');
    }
);

// School Settings ///////
Route::group(
    [
        'prefix' => 'system-settings',
        'namespace' => 'App\Http\Controllers\Admin',
    ],
    function ($router) {
        Route::get('/school', 'SchoolSettingsController@index');
        Route::get('/smtp', 'SmtpSettingsController@index');
    }
);

/////// SCHOOL SESSION CRUD //////
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

// routes action for users Auth ////////
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

/////// Subject CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'subject',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/add', 'SubjectController@store');
        Route::put('/update', 'SubjectController@update');
        Route::delete('/delete', 'SubjectController@destroy');
        Route::get('/all', 'SubjectController@index');
        Route::get('/fetch-one-subject', 'SubjectController@fetch_one_subject');
    }
);

/////// Subject Group CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'subject-group',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/add', 'SubjectGroupController@store');
        Route::delete('/delete', 'SubjectGroupController@destroy');
    }
);

/////// Subject to class assignment CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'subject-class-assign',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/add', 'SubjectClassAssignController@store');
        Route::delete('/delete', 'SubjectClassAssignController@destroy');
    }
);

/////// Subject to staff assignment CRUD
Route::group(
    [
        'middleware' => ['jwt.verify', 'admin.access'],
        'prefix' => 'subject-staff-assign',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/add', 'StaffSubjectAssignController@store');
        Route::delete('/delete', 'StaffSubjectAssignController@destroy');
    }
);

////// Newsletter CRUD
Route::group(
    [
        'prefix' => 'newsletter',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/add', 'NewsletterController@store')->middleware([
            'jwt.verify',
            'admin.access',
        ]);
        Route::put('/update', 'NewsletterController@update')->middleware([
            'jwt.verify',
            'admin.access',
        ]);
        Route::delete('/delete', 'NewsletterController@destroy')->middleware([
            'jwt.verify',
            'admin.access',
        ]);
        Route::get('/all', 'NewsletterController@index')->middleware([
            'jwt.verify',
        ]);
        Route::get(
            '/fetch-one-subject',
            'NewsletterController@fetch_one_newsletter'
        )->middleware(['jwt.verify']);
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
