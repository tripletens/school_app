<?php

namespace App\Http\Controllers;

use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\Newsletter;
use App\Validations\ErrorValidation;
use App\Validations\NewsletterValidators;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch all newsletters

        $roles = DBHelpers::all_data(Newsletter::class);

        return ResponseHelper::success_response(
            'All Newsletters fetched successfully',
            $roles
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save newsletter
        if ($request->isMethod('post')) {
            $validate = NewsletterValidators::validate_rules(
                $request,
                'register_newsletter'
            );

            if (!$validate->fails() && $validate->validated()) {

                $data = [
                    'title' => $request->title ? $request->title : null ,
                    'description' => $request->description ? $request->description : null,
                    'image_url' => $request->image_url ? $request->image_url : null,
                    'video_url' => $request->video_url ? $request->video_url : null
                ];

                $create = DBHelpers::create_query(Newsletter::class, $data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Newsletter created successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Newsletter creation failed, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['title', 'description'];
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function fetch_one_newsletter(Request $request)
    {
        //

        if ($request->isMethod('get')) {
            $validate = NewsletterValidators::validate_rules(
                $request,
                'fetch_newsletter_by_id'
            );

            if (!$validate->fails() && $validate->validated()) {

                $id = $request->id;

                $data = [
                    "id" => $id,
                ];


                if (!DBHelpers::exists(Subject::class, ['id' => $id])) {
                    return ResponseHelper::error_response(
                        'Update failed, Newsletter not found',
                        '',
                        401
                    );
                }

                $newsletter = DBHelpers::query_filter_first(Newsletter::class,[
                    "id" => $id,
                ]);

                return ResponseHelper::success_response(
                    'Newsletter fetched successful',
                    $newsletter
                );

            } else {
                $errors = json_decode($validate->errors());
                $props = [
                    "id",
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // edit newsletter
        if ($request->isMethod('put')) {
            $validate = NewsletterValidators::validate_rules(
                $request,
                'update_newsletter'
            );

            if (!$validate->fails() && $validate->validated()) {
                $id = $request->id;

                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_url' => $request->image_url ? $request->image_url : null,
                    'video_url' => $request->video_url ? $request->video_url : null,
                ];

                if (!DBHelpers::exists(Newsletter::class, ['id' => $id])) {
                    return ResponseHelper::error_response(
                        'Update failed, Newsletter not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query(
                    Newsletter::class,
                    $data,
                    $id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Update failed, Database insertion issues',
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
                $props = ['title', 'description'];
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // delete newsletter by id
        if ($request->isMethod('delete')) {
            $validate = NewsletterValidators::validate_rules($request, 'delete_newsletter');

            if (!$validate->fails() && $validate->validated()) {
                $id = $request->id;

                $create = DBHelpers::delete_query(Newsletter::class, $id);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Newsletter deletion was successful',
                        null,
                        200
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Delete failed, Database issues',
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
}
