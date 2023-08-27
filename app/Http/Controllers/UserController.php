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
use App\Services\ExcelSpreadSheet;
use App\Models\Staff;
use App\Models\User;
use App\Models\UserBio;

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

    public static function staff_upload($file, $role)
    {
        $excel_sheet = new ExcelSpreadSheet($file);
        $row_range = range(2, $excel_sheet->row_limit);
        $column_range = range('F', $excel_sheet->column_limit);
        $startcount = 2;
        $data = [];

        $total_row = 0;
        $total_added = 0;
        $total_already_exists = 0;

        foreach ($row_range as $row) {
            $email = $excel_sheet->sheet->getCell('A' . $row)->getValue();
            $surname = $excel_sheet->sheet->getCell('B' . $row)->getValue();
            $first_name = $excel_sheet->sheet->getCell('C' . $row)->getValue();
            $other_name = $excel_sheet->sheet->getCell('D' . $row)->getValue();
            $phone = $excel_sheet->sheet->getCell('E' . $row)->getValue();

            $total_row++;

            if (
                !User::query()
                    ->where('email', $email)
                    ->exists()
            ) {
                ++$total_added;
                $user_data = [
                    'first_name' => $first_name,
                    'other_name' => $other_name,
                    'surname' => $surname,
                    'phone' => $phone,
                    'email' => $email,
                    'role' => $role,
                ];

                $user_data = json_encode($user_data);

                $user = [
                    'email' => $email,
                    'role' => $role,
                    'data' => $user_data,
                ];

                $create = DBHelpers::create_query(User::class, $user);

                $bio_data = [
                    'first_name' => $first_name,
                    'other_name' => $other_name,
                    'surname' => $surname,
                    'phone' => $phone,
                    'uid' => $create->id,
                ];

                DBHelpers::create_query(UserBio::class, $bio_data);
            } else {
                ++$total_already_exists;
            }
        }

        return [
            'total_row' => $total_row,
            'total_added' => $total_added,
            'total_already_exists' => $total_already_exists,
        ];
    }

    public function upload_user(Request $request, UserValidators $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'upload');

            if (!$validate->fails() && $validate->validated()) {
                $upload = [];
                $file = $request->file('excel');
                if ($request->type == 'student') {
                } else {
                    $upload = UserController::staff_upload(
                        $file,
                        $request->role
                    );
                }

                return ResponseHelper::success_response(
                    'Upload was successfully',
                    $upload
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['excel', 'type', 'role'];
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
