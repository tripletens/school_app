<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\EmailTemplateValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    //

    public function index()
    {
        $email = DBHelpers::query_filter(EmailTemplate::class, [
            'is_active' => 1,
        ]);
        return ResponseHelper::success_response(
            'All email template fetched successful',
            $email
        );
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
                        'Email Template added successful',
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

    public function update(Request $request, EmailTemplateValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(EmailTemplate::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, Email Template class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    EmailTemplate::class,
                    $request->only(['name', 'subject', 'body']),
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
