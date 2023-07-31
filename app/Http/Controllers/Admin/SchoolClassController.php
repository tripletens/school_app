<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\SchoolClass;
use App\Helpers\DBHelpers;
use App\Validations\SchoolClassValidators;
use App\Validations\ErrorValidation;

class SchoolClassController extends Controller
{
    //

    public function activate(Request $request, SchoolClassValidators $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    SchoolClass::class,
                    ['is_active' => 1],
                    $request->id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Activation failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Class activated successfully',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['id'];
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

    public function deactivate(Request $request, SchoolClassValidators $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    SchoolClass::class,
                    ['is_active' => 0],
                    $request->id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Deactivation failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Class deactivated successfully',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['id'];
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

    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolClassValidators::validate_rules(
                $request,
                'update'
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

                $update = DBHelpers::update_query_v2(
                    SchoolClass::class,
                    $request->only([
                        'name',
                        'arm',
                        'staff',
                        'class_level',
                        'class_category',
                        'report_card_template',
                        'mid_term_template',
                    ]),
                    $request->id
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
                $props = [
                    'name',
                    'arm',
                    'staff',
                    'id',
                    'class_level',
                    'class_category',
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

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolClassValidators::validate_rules(
                $request,
                'create'
            );

            if (!$validate->fails() && $validate->validated()) {
                $create = DBHelpers::create_query(
                    SchoolClass::class,
                    $request->all()
                );

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
                    'name',
                    'arm',
                    'staff',
                    'class_level',
                    'class_category',
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

    public function classes()
    {
        $classes = DBHelpers::query_filter(SchoolClass::class, [
            'is_active' => 1,
        ]);
        return ResponseHelper::success_response('All school classes', $classes);
    }

    public function index()
    {
        $classes = DBHelpers::with_query(SchoolClass::class, [
            'level',
            'category',
        ]);
        return ResponseHelper::success_response('All school classes', $classes);
    }
}
