<?php

namespace App\Http\Controllers;

use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SubjectClassAssign;
use App\Validations\ErrorValidation;
use App\Validations\SubjectClassAssignValidators;
use Illuminate\Http\Request;

class SubjectClassAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if ($request->isMethod('post')) {
            $validate = SubjectClassAssignValidators::validate_rules(
                $request,
                'register_subject_class_assign'
            );

            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'subject_id' => $request->subject_id,
                    'class_id' => $request->class_id
                ];

                $create = DBHelpers::create_query(SubjectGroup::class, $data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Subject added to class successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Subject could not be added to class, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['subject_id', 'class_id'];
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // delete a subject from a class
        if ($request->isMethod('delete')) {
            $validate = SubjectClassAssignValidators::validate_rules($request, 'delete_subject_group');

            if (!$validate->fails() && $validate->validated()) {
                $subject_id = $request->subject_id;
                $class_id = $request->class_id;

                // find the class where subject_id and class_id are available and then delete

                if (!DBHelpers::exists(SubjectGroup::class, ['subject_id' => $subject_id, 'class_id' => $class_id])) {
                    return ResponseHelper::error_response(
                        'Update failed, Subject not in class',
                        '',
                        401
                    );
                }

                // here it exists so we delete them

                $delete = DBHelpers::delete_query_multi(SubjectClassAssign::class, ['subject_id' => $subject_id, 'class_id' => $class_id]);

                if ($delete) {
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
