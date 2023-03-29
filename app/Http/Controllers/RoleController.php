<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Role;
use App\Helpers\DBHelpers;
use App\Validations\AuthValidators;
use App\Validations\ErrorValidation;

class RoleController extends Controller
{
    //

    public function delete(Request $request)
    {
        if ($request->isMethod('delete')) {
            $validate = AuthValidators::validate_rules($request, 'update_role');

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $id = $request->id;
                $slug = str_replace(' ', '_', $name);

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::update_query(Role::class, $data, $id);

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
                $props = ['name', 'id'];
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
        if ($request->isMethod('put')) {
            $validate = AuthValidators::validate_rules($request, 'update_role');

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $id = $request->id;
                $slug = str_replace(' ', '_', $name);

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::update_query(Role::class, $data, $id);

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
                $props = ['name', 'id'];
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

    public function roles()
    {
        try {
            $roles = Role::all();

            return ResponseHelper::success_response(
                'All Roles fetched successful',
                $roles
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
                'register_role'
            );

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $slug = str_replace(' ', '_', $name);

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::create_query(Role::class, $data);

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
}
