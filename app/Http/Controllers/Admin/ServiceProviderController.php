<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\ServiceProvidersValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\ServiceProviders;

class ServiceProviderController extends Controller
{
    //

    public function activate(Request $request, ServiceProvidersValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query_v3(
                    ServiceProviders::class,
                    ['is_active' => 1],
                    ['name' => $request->name]
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Deactivation failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Service provider activated successfully',
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

    public function deactivate(Request $request, ServiceProvidersValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query_v3(
                    ServiceProviders::class,
                    ['is_active' => 0],
                    ['name' => $request->name]
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Deactivation failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Service Provider deactivated successfully',
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

    public function index()
    {
        $providers = DBHelpers::all_data(ServiceProviders::class);
        return ResponseHelper::success_response(
            'All services providers fetched successfully',
            $providers
        );
    }
}
