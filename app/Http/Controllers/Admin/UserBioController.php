<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\UserBio;
use App\Models\User;
use App\Helpers\DBHelpers;
use App\Validations\StaffValidator;
use App\Validations\ErrorValidation;

class UserBioController extends Controller
{
    //

    public function user($role)
    {
        $user = DBHelpers::with_where_query_filter(
            User::class,
            ['bio', 'role'],
            ['role' => $role]
        );

        return ResponseHelper::success_response(
            'All users fetched successfully',
            $user
        );
    }

    public function create_staff(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = StaffValidator::validate_rules(
                $request,
                'register_staff'
            );

            if (!$validate->fails() && $validate->validated()) {
                $user_data = [
                    'first_name' => $request->first_name,
                    'other_name' => $request->other_name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'role' => $request->role,
                ];
                $user_data = json_encode($user_data);
                $user = [
                    'email' => $request->email,
                    'role' => $request->role,
                    'data' => $user_data,
                    'full_name' =>
                        $request->first_name .
                        ' ' .
                        $request->surname .
                        ' ' .
                        $request->other_name,
                ];

                $create = DBHelpers::create_query(User::class, $user);

                $staff_data = [
                    'first_name' => $request->first_name,
                    'other_name' => $request->other_name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'uid' => $create->id,
                ];

                $create = DBHelpers::create_query(UserBio::class, $staff_data);

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
}
