<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\SchoolSettingsValidator;

use App\Validations\FormDataValidation;

use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SchoolSettings;

use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SchoolSettingsController extends Controller
{
    //

    public function index()
    {
        $settings = DBHelpers::first_data(SchoolSettings::class);
        return ResponseHelper::success_response(
            'School settings fetched successfully',
            $settings
        );
    }

    public function personification(
        FormDataValidation $request,
        SchoolSettingsValidator $val
    ) {
        // $test = file_get_contents('php://input');
        // echo $test;

        ///return $request->all();

        // if ($request->id) {
        //     echo 'has';
        // }

        // return $test = file_get_contents('php://input');
        // echo $test;

        // $data = Request::createFromGlobals()->all();
        // print_r($data);

        // return $request->getContent()->toArray();

        // return dd($request->files->get('logo'));

        // $req_data = Request::createFromGlobals()->getContent();

        // $re = explode(';', $req_data);

        // print_r($re);

        // ->getContent()
        // ->get('id');

        /// return $request->input('id');

        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'logo' => 'required|mimes:pdf,doc,docx,xls,jpg,jpeg,png,gif',
        ]);

        if ($validator->fails()) {
            // $response['response'] = $validator->messages();
            // $this->result->status = false;
            // $this->result->status_code = 422;
            // $this->result->message = $response;

            return response()->json($validator->messages());
        }

        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'personification');

            //   Request::createFromGlobals()->get('username');

            //  $username = Request::createFromGlobals()->get('username')

            // $cloud = new CloudinaryService();

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

                $logo_url = '';
                if ($request->hasFile('logo')) {
                    $logo_url = $cloud->image_upload(
                        'images',
                        $request->file('logo')
                    );
                }

                $update_data = [
                    'logo_url' => $logo_url,
                    'bg_color' => $request->bg_color,
                ];

                $update = DBHelpers::update_query(
                    SchoolSettings::class,
                    $update_data,
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
                    'Update was successfully',
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
                        'School Settings added successfully',
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
