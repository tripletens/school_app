<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Func;

use App\Helpers\ResponseHelper;
use App\Models\SchoolSession;
use App\Helpers\DBHelpers;
use App\Validations\SchoolSessionValidators;
use App\Validations\ErrorValidation;

class SchoolSessionController extends Controller
{
    //

    public function school_session_years()
    {
        return Func::school_session_builder();
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolSessionValidators::validate_rules(
                $request,
                'register_school_session'
            );

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $slug = str_replace(' ', '_', $name);

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::create_query(
                    SchoolTermSession::class,
                    $data
                );

                if (!$create) {
                    return ResponseHelper::error_response(
                        'Registration failed, Database insertion issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Registration was successful',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = [
                    'school_session',
                    'school_term',
                    'start_date',
                    'end_date',
                    'day_duration',
                    'school_duration',
                    'vacation',
                    'holiday',
                    'status',
                    'is_active',
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
