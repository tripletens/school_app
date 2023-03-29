<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\SchoolClass;
use App\Helpers\DBHelpers;
use App\Validations\AuthValidators;
use App\Validations\ErrorValidation;

class SchoolClassController extends Controller
{
    //

    public function register_class(Request $request)
    {
        if ($request->isMethod('post')) {
            /// return $request;

            $validate = AuthValidators::validate_rules(
                $request,
                'register_class'
            );

            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'name' => $request->name,
                    'arm' => $request->arm,
                    'staff' => $request->staff,
                    'no_of_students' => 0,
                ];

                $create = DBHelpers::create_query(SchoolClass::class, $data);

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
                $props = ['name', 'arm', 'staff'];
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

    public function classes()
    {
        $classes = SchoolClass::all();
        return ResponseHelper::success_response('All school classes', $classes);
    }
}
