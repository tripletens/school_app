<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\SmsServicesValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SmsServices;
use App\Models\ServiceProviders;
use App\Services\Termii;

class SmsServicesController extends Controller
{
    //

    public function balance($provider)
    {
        $data = DBHelpers::query_filter_first(SmsServices::class, [
            'name' => $provider,
        ]);

        if (!$data) {
            return ResponseHelper::error_response(
                'SMS service provider, no avaliable',
                '',
                401
            );
        }

        $name = $data->name;
        $api_key = $data->api_key;
        switch ($name) {
            case 'termii':
                $res = Termii::balance($api_key);
                $res = json_decode($res);
                if (isset($res->balance)) {
                    return ResponseHelper::success_response(
                        'SMS balance fetched successfully',
                        $res
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Error trying to retrive SMS balance, try again',
                        null,
                        401
                    );
                }

                break;

            default:
                return ResponseHelper::error_response(
                    'No Service provider (SMS), not available in the system, add on the settings page',
                    '',
                    401
                );
                break;
        }
    }

    public static function provider_active()
    {
        $active = DBHelpers::query_filter_first(SmsServices::class, [
            'is_active' => 1,
        ]);

        if (!$active) {
            return ResponseHelper::error_response(
                'No active SMS service provider, activate on the settings page',
                '',
                401
            );
        }

        return $active;
    }

    public static function service_provider_exists($name)
    {
        return DBHelpers::exists(ServiceProviders::class, ['name' => $name]);
    }

    public static function termii($data)
    {
        $api_key = $data->api_key;
        $data = [
            'api_key' => $api_key,
            'to' => '2348134873993',
            'from' => 'fastbeep',
            'sms' => 'Hi there, testing Termii ',
            'type' => 'plain',
            'channel' => 'generic',
        ];

        $data = json_encode($data);
        return Termii::send($data);
    }

    public function send_sms()
    {
        if (!SmsServicesController::service_provider_exists('sms')) {
            return ResponseHelper::error_response(
                'Service provider (SMS), not available in the system, add on the settings page',
                '',
                401
            );
        }

        $provider = SmsServicesController::provider_active();
        $name = $provider->name;

        switch ($name) {
            case 'termii':
                $res = SmsServicesController::termii($provider);
                if (isset($res->code) && $res->code == 'ok') {
                    return ResponseHelper::success_response(
                        'SMS sent successfully',
                        $res
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Sms not sent',
                        $res,
                        401
                    );
                }

                break;

            default:
                return ResponseHelper::error_response(
                    'No Service provider (SMS), not available in the system, add on the settings page',
                    '',
                    401
                );
                break;
        }
    }

    public function index()
    {
        $sms = DBHelpers::all_data(SmsServices::class);
        return ResponseHelper::success_response(
            'All SMS services fetched successfully',
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
                        'SMS service added successfully',
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

            DBHelpers::update_query(
                SmsServices::class,
                ['is_active' => 1],
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
                'All SMS services deactivated successfully',
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

                // DBHelpers::update_query(
                //     SmsServices::class,
                //     ['is_active' => 0],
                //     0
                // );

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
                    'Activation was successfully',
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
                    'Update was successfully',
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
