<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Helpers\DBHelpers;
use App\Models\Student;
use App\Models\Subject;
use App\Validations\SubjectValidators;
use App\Validations\ErrorValidation;
use App\Validations\StudentValidators;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch all subjects

        $roles = DBHelpers::all_data(Subject::class);

        return ResponseHelper::success_response(
            'All Subjects fetched successful',
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = SubjectValidators::validate_rules(
                $request,
                'register_subject'
            );

            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                ];

                $create = DBHelpers::create_query(Subject::class, $data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Subject created successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Subject creation failed, Database insertion issues',
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
    public function update(Request $request)
    {
        if ($request->isMethod('put')) {
            $validate = SubjectValidators::validate_rules(
                $request,
                'update_subject'
            );

            if (!$validate->fails() && $validate->validated()) {
                $title = $request->title;
                $description = $request->description;
                $id = $request->id;

                $data = [
                    "title" => $title,
                    "description" => $description
                ];

                $update = DBHelpers::update_query(
                    Student::class,
                    $data,
                    $id
                );

                if ($update) {
                    return ResponseHelper::success_response(
                        'Update was successful',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Update failed, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = [
                    "title",
                    "description",
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->isMethod('delete')) {
            $validate = SubjectValidators::validate_rules($request, 'delete_subject');

            if (!$validate->fails() && $validate->validated()) {
                $id = $request->id;

                $create = DBHelpers::delete_query(Subject::class, $id);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Delete was successful',
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
