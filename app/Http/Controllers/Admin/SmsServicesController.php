<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\SmsServicesValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SmsServices;

class SmsServicesController extends Controller
{
    //

    public function index()
    {
        $sms = DBHelpers::all_data(SmsServices::class);
        return ResponseHelper::success_response(
            'All SMS services fetched successful',
            $sms
        );
    }

    public function create(Request $request, SmsServicesValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $create = DBHelpers::create_query(
                    SmsServices::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'SMS service added successful',
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
                $props = ['provider_name'];
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

    public function deactivate(Request $request, SmsServicesValidator $val)
    {
        if ($request->isMethod('post')) {
            $update = DBHelpers::update_query(
                SmsServices::class,
                ['is_active' => 0],
                0
            );

            if (!$update) {
                return ResponseHelper::error_response(
                    'Deactivation failed, Database updated issues',
                    '',
                    401
                );
            }

            return ResponseHelper::success_response(
                'All SMS services deactivated successful',
                null
            );
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }

    public function active_provider(Request $request, SmsServicesValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'active');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(SmsServices::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Activation failed, Sms Service class not found',
                        '',
                        401
                    );
                }

                DBHelpers::update_query(
                    SmsServices::class,
                    ['is_active' => 0],
                    0
                );

                $update = DBHelpers::update_query(
                    SmsServices::class,
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
                    'Activation was successful',
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

    public function update(Request $request, SmsServicesValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(SmsServices::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, Sms Service class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    SmsServices::class,
                    $request->only([
                        'username',
                        'sender_id',
                        'api_key',
                        'token_key',
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
}
