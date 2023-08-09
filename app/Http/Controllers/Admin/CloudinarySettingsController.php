<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Validations\CloudinarySettingsValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\CloudinarySettings;
use App\Services\CloudinaryService;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinarySettingsController extends Controller
{
    //

    public function testing(Request $request)
    {
        $data = DBHelpers::first_data(CloudinarySettings::class);

        if ($data) {
            $cloud = new CloudinaryService(
                $data->name,
                $data->api_key,
                $data->secret_key,
                $data->cloudinary_url
            );

            $res = $cloud->image_upload('test', $request->file('image'));
            return $res['secure_url'];
        } else {
            return ResponseHelper::error_response(
                'Upload failed,  Cloudinary Settings class not found',
                '',
                401
            );
        }
    }

    public function upload_image(Request $request)
    {
        $data = DBHelpers::first_data(CloudinarySettings::class);

        if ($data) {
            $cloud = new CloudinaryService(
                $data->name,
                $data->api_key,
                $data->secret_key,
                $data->cloudinary_url
            );

            $res = $cloud->image_upload('images', $request->file('image'));
            return $res['secure_url'];
        } else {
            return ResponseHelper::error_response(
                'Upload failed,  Cloudinary Settings class not found',
                '',
                401
            );
        }
    }

    public function toggle(Request $request, CloudinarySettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'toggle');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(CloudinarySettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Toggle failed,  Cloudinary Settings class not found',
                        '',
                        401
                    );
                }

                $current = DBHelpers::first_data(CloudinarySettings::class);
                $msg = '';

                if ($current->is_active == 1) {
                    $update = DBHelpers::update_query(
                        CloudinarySettings::class,
                        ['is_active' => 0],
                        $request->id
                    );
                    $msg = 'Cloudinary settings deactivated successfully';
                } else {
                    $update = DBHelpers::update_query(
                        CloudinarySettings::class,
                        ['is_active' => 1],
                        $request->id
                    );
                    $msg = 'Cloudinary settings activated successfully';
                }

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Cloudinary toggle failed, Database updated issues',
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
        $settings = DBHelpers::first_data(CloudinarySettings::class);
        return ResponseHelper::success_response(
            'Cloudinary settings fetched successfully',
            $settings
        );
    }

    public function update(Request $request, CloudinarySettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(CloudinarySettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, Cloudinary settings class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    CloudinarySettings::class,
                    $request->only([
                        'name',
                        'api_key',
                        'secret_key',
                        'cloudinary_url',
                        'is_active',
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
                $props = [
                    'id',
                    'name',
                    'api_key',
                    'secret_key',
                    'cloudinary_url',
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

    public function create(Request $request, CloudinarySettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $settings = CloudinarySettings::query()->get();
                if (count($settings) > 0) {
                    return ResponseHelper::success_response(
                        'Cloudinary Settings added already, you can only update it',
                        null
                    );
                }

                $create = DBHelpers::create_query(
                    CloudinarySettings::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'Cloundinary Settings added successfully',
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
                    'api_key',
                    'secret_key',
                    'cloudinary_url',
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
