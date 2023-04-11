<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\SchoolTermSessionValidators;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\SchoolTermSession;
use App\Helpers\DBHelpers;

class SchoolTermSessionController extends Controller
{
    //

    public function activate_term(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolTermSessionValidators::validate_rules(
                $request,
                'activate_term'
            );

            if (!$validate->fails() && $validate->validated()) {
                $all_update = [
                    'is_active' => 0,
                ];

                $update = [
                    'is_active' => 1,
                ];

                DBHelpers::update_query(
                    SchoolTermSession::class,
                    $all_update,
                    null
                );

                $activate = DBHelpers::update_query(
                    SchoolTermSession::class,
                    $update,
                    $request->id
                );

                if ($activate) {
                    return ResponseHelper::success_response(
                        'School term activated successful',
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
        if ($request->isMethod('put')) {
            $validate = SchoolTermSessionValidators::validate_rules(
                $request,
                'update_term'
            );

            if (!$validate->fails() && $validate->validated()) {
                $name = $request->name;
                $id = $request->id;
                $slug = str_replace(' ', '_', $name);

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                $create = DBHelpers::update_query(
                    SchoolTermSession::class,
                    $data,
                    $id
                );

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

    public function terms()
    {
        $terms = DBHelpers::all_data(SchoolTermSession::class);
        if (!$terms) {
            return ResponseHelper::error_response(
                'Get terms failed, Database retrival issues',
                '',
                401
            );
        }

        return ResponseHelper::success_response(
            'All terms retrived successful',
            $terms
        );
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SchoolTermSessionValidators::validate_rules(
                $request,
                'register_term'
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
