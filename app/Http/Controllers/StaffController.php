<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ResponseHelper;
use App\Models\Staff;
use App\Models\User;
use App\Helpers\DBHelpers;
use App\Validations\AuthValidators;
use App\Validations\ErrorValidation;

class StaffController extends Controller
{
    //

    public function staffs()
    {
        try {
            $staffs = Staff::with(['user', 'role'])->get();

            return ResponseHelper::success_response(
                'All staffs fetched successful',
                $staffs
            );
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidators::validate_rules(
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
                        'Registration was successful',
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
}
