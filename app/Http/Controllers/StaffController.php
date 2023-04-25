<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ResponseHelper;
use App\Models\Staff;
use App\Models\User;
use App\Helpers\DBHelpers;
use App\Validations\StaffValidator;
use App\Validations\ErrorValidation;

class StaffController extends Controller
{
    //

    public function update(Request $request)
    {
        if ($request->isMethod('put')) {
            $validate = StaffValidator::validate_rules(
                $request,
                'update_staff'
            );

            if (!$validate->fails() && $validate->validated()) {
                $first_name = $request->first_name;
                $other_name = $request->other_name;
                $surname = $request->surname;
                $email = $request->email;
                $phone = $request->phone;
                $role = $request->role;
                $uid = $request->uid;

                $exists = DBHelpers::exists(User::class, ['id', $uid]);

                if (!$exists) {
                    return ResponseHelper::error_response(
                        'User not found',
                        '',
                        404
                    );
                }

                $user = [
                    'role' => $role,
                    'email' => $email,
                ];

                $staff = [
                    'first_name' => $first_name,
                    'other_name' => $other_name,
                    'surname' => $surname,
                    'phone' => $phone,
                    'uid' => $uid,
                ];

                $update = DBHelpers::insert_update_query(
                    'staff',
                    ['uid', $uid],
                    $staff
                );

                $create = DBHelpers::update_query(User::class, $user, $uid);

                // $create = DBHelpers::update_query(Role::class, $data, $id);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Update was successful',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Update failed, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = [
                    'first_name',
                    'other_name',
                    'email',
                    'surname',
                    'phone',
                    'id',
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

    public function staff(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = StaffValidator::validate_rules($request, 'staff');

            if (!$validate->fails() && $validate->validated()) {
                $exists = DBHelpers::exists(User::class, [
                    'id' => $request->uid,
                ]);
                if (!$exists) {
                    return ResponseHelper::error_response(
                        'User not found, Contact admin',
                        '',
                        404
                    );
                }

                $staff = DBHelpers::with_where_query_filter(
                    User::class,
                    ['staff', 'role', 'school_class'],
                    ['id' => $request->uid]
                );

                if ($staff) {
                    return ResponseHelper::success_response(
                        'Staff data retrived successful',
                        $staff
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Staff data not retrived, try again',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['uid'];
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
