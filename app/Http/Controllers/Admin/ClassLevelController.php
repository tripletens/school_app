<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\ClassLevelValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\ClassLevel;

class ClassLevelController extends Controller
{
    //

    public function index()
    {
        $class_level = DBHelpers::query_filter(ClassLevel::class, [
            'is_active' => 1,
        ]);
        return ResponseHelper::success_response(
            'All Class Level fetched successful',
            $class_level
        );
    }

    public function create(Request $request, ClassLevelValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $slug = str_replace(' ', '_', $request->name);
                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::create_query(ClassLevel::class, $data);
                if ($create) {
                    return ResponseHelper::success_response(
                        'Class level added successful',
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
                $props = ['name'];
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

    public function activate(Request $request, ClassLevelValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    ClassLevel::class,
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
                    'Class level activated successful',
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

    public function deactivate(Request $request, ClassLevelValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    ClassLevel::class,
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
                    'Class level deactivated successful',
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

    public function update(Request $request, ClassLevelValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(ClassLevel::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, Class level class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    ClassLevel::class,
                    $request->only(['name', 'slug']),
                    $request->id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Update failed, Database updated issues',
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
                $props = ['id', 'name'];
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
