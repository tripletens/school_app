<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\SmtpSettingsValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\StmpSettings;

class SmtpSettingsController extends Controller
{
    //

    public function toggle(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('put')) {
            $validate = $val->validate_rules($request, 'toggle');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(StmpSettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Toggle failed,  SMTP Settings class not found',
                        '',
                        401
                    );
                }

                $current = DBHelpers::first_data(StmpSettings::class);
                $msg = '';

                if ($current->is_active == 1) {
                    $update = DBHelpers::update_query(
                        StmpSettings::class,
                        ['is_active' => 0],
                        $request->id
                    );
                    $msg = 'SMTP settings deactivated successfully';
                } else {
                    $update = DBHelpers::update_query(
                        StmpSettings::class,
                        ['is_active' => 1],
                        $request->id
                    );
                    $msg = 'SMTP settings activated successfully';
                }

                if (!$update) {
                    return ResponseHelper::error_response(
                        'SMTP toggle failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response($msg, null);
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

    public function index()
    {
        $settings = DBHelpers::first_data(StmpSettings::class);
        return ResponseHelper::success_response(
            'SMTP settings fetched successfully',
            $settings
        );
    }

    public function update(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(StmpSettings::class, [
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
                    StmpSettings::class,
                    $request->only([
                        'hostname',
                        'protocol',
                        'username',
                        'password',
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
                    'Update was successfully',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['id', 'hostname', 'protocol', 'username', 'password'];
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

    public function create(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $settings = StmpSettings::query()->get();
                if (count($settings) > 0) {
                    return ResponseHelper::success_response(
                        'SMTP Settings added already, you can only update it',
                        null
                    );
                }

                $create = DBHelpers::create_query(
                    StmpSettings::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'SMTP Settings added successfully',
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
                $props = ['hostname', 'protocol', 'username', 'password'];
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
