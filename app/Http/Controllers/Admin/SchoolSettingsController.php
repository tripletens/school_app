<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\SchoolSettingsValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SchoolSettings;

class SchoolSettingsController extends Controller
{
    //

    public function index()
    {
        $settings = DBHelpers::first_data(SchoolSettings::class);
        return ResponseHelper::success_response(
            'School settings fetched successful',
            $settings
        );
    }

    public function update(Request $request, SchoolSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(SchoolSettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, School settings class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    SchoolSettings::class,
                    $request->only([
                        'title',
                        'name',
                        'email',
                        'phone',
                        'address',
                        'running_year',
                        'currency',
                        'facebook',
                        'twitter',
                        'instagram',
                        'youtube',
                        'created_by',
                    ]),
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
                $props = ['id', 'running_year'];
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

    public function create(Request $request, SchoolSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $settings = SchoolSettings::query()->get();
                if (count($settings) > 0) {
                    return ResponseHelper::success_response(
                        'School Settings added already, you can only update it',
                        null
                    );
                }

                $create = DBHelpers::create_query(
                    SchoolSettings::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'School Settings added successful',
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
                $props = ['running_year'];
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
