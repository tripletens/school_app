<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\SchoolClass;
use App\Helpers\DBHelpers;
use App\Validations\SchoolClassValidators;
use App\Validations\ErrorValidation;

class SchoolClassController extends Controller
{
    //

    public function update(Request $request)
    {
        if ($request->isMethod('put')) {
            $validate = SchoolClassValidators::validate_rules(
                $request,
                'update_class'
            );

            if (!$validate->fails() && $validate->validated()) {
                $id = $request->id;

                $data = [
                    'name' => $request->name,
                    'arm' => $request->arm,
                    'staff' => $request->staff,
                ];

                if (!DBHelpers::exists(SchoolClass::class, ['id' => $id])) {
                    return ResponseHelper::error_response(
                        'Update failed, School class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query(
                    SchoolClass::class,
                    $data,
                    $id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Update failed, Database insertion issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Update was successful',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['name', 'arm', 'staff', 'id'];
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

    public function register_class(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolClassValidators::validate_rules(
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
