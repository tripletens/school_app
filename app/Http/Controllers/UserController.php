<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Helpers\ResponseHelper;
use App\Helpers\DBHelpers;
use App\Models\Student;
use App\Models\Subject;
use App\Validations\UserValidators;
use App\Validations\ErrorValidation;
use App\Validations\StudentValidators;

class UserController extends Controller
{
    //

    protected static $user;
    protected static $uid;

    public function __construct()
    {
        if (!JWTAuth::getToken()) {
            //  return 'no token';

            ///  throw new JWTException('Token not provided');
        }

        // self::$uid = JWTAuth::parseToken()->authenticate()->id;
    }

    public function create(UserValidators $val, Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');

            if (!$validate->fails() && $validate->validated()) {
                $user_data = [
                    'first_name' => $request->first_name,
                    'other_name' => $request->other_name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => $request->role,
                ];

                $user_data = json_encode($user_data);

                $user = [
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'role' => $request->role,
                    'data' => $user_data,
                ];

                $create = DBHelpers::create_query(User::class, $user);

                $staff_data = [
                    'first_name' => $request->first_name,
                    'other_name' => $request->other_name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'uid' => $create->id,
                    'role' => $request->role,
                ];

                $create = DBHelpers::create_query(Staff::class, $staff_data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Registration was successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Registration failed, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = [
                    'first_name',
                    'other_name',
                    'surname',
                    'role',
                    'phone',
                    'password',
                    'email',
                ];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }

    public function dashboard()
    {
        return 'heyye';
    }
}
