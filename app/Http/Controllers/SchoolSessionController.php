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

    public function update(Request $request)
    {
        if ($request->isMethod('put')) {
            $validate = SchoolSessionValidators::validate_rules(
                $request,
                'update'
            );

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $id = $request->id;

                $data = [
                    'school_session' => $request->school_session,
                    'school_term' => $request->school_term,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'day_duration' => $request->day_duration,
                ];

                $create = DBHelpers::update_query(
                    SchoolSession::class,
                    $data,
                    $id
                );

                if ($create) {
                    return ResponseHelper::success_response(
                        'Update was successfully',
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
                    'school_session',
                    'school_term',
                    'start_date',
                    'end_date',
                    'day_duration',
                    'school_duration',
                    'vacation',
                    'holiday',
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

    public function activate_session(Request $request)
    {
        if ($request->isMethod('put')) {
            $validate = SchoolSessionValidators::validate_rules(
                $request,
                'activate_session'
            );

            if (!$validate->fails() && $validate->validated()) {
                DBHelpers::update_query(
                    SchoolSession::class,
                    [
                        'is_active' => 0,
                    ],
                    null
                );

                $update = DBHelpers::update_query(
                    SchoolSession::class,
                    [
                        'is_active' => 1,
                    ],
                    $request->session_id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Update failed, Database insertion issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Update was successfully',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['session_id'];

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

    public function school_sessions()
    {
        $sessions = DBHelpers::with_query(SchoolSession::class, ['term']);

        if (!$sessions) {
            return ResponseHelper::error_response(
                'Retrival failed, Database collection issues',
                '',
                401
            );
        }

        return ResponseHelper::success_response(
            'All school session was successfully',
            $sessions
        );
    }

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
                $create = DBHelpers::create_query(
                    SchoolSession::class,
                    $request->all()
                );

                if (!$create) {
                    return ResponseHelper::error_response(
                        'Registration failed, Database insertion issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Registration was successfully',
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
