<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Role;
use App\Models\MarqueeMsg;
use App\Helpers\DBHelpers;
use App\Validations\MarqueeValidator;
use App\Validations\ErrorValidation;

class MarqueeController extends Controller
{
    //

    public function index()
    {
        $marquee = DBHelpers::query_filter(MarqueeMsg::class, [
            'is_active' => 1,
        ]);
        return ResponseHelper::success_response(
            'All Marquee message fetched successfully',
            $marquee
        );
    }

    public function activate(Request $request, MarqueeValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    MarqueeMsg::class,
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
                    'Marquee message activated successfully',
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

    public function deactivate(Request $request, MarqueeValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'deactivate');
            if (!$validate->fails() && $validate->validated()) {
                $update = DBHelpers::update_query(
                    MarqueeMsg::class,
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
                    'Marquee message deactivated successfully',
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

    public function create(Request $request, EmailTemplateValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $create = DBHelpers::create_query(
                    EmailTemplate::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'Email Template added successfully',
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
