<?php

namespace App\Http\Controllers;

use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\SubjectGroup;
use Illuminate\Http\Request;
use App\Validations\SubjectGroupValidators;
use App\Validations\ErrorValidation;

class SubjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch all the groups we have

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // add the
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
            $validate = SubjectGroupValidators::validate_rules(
                $request,
                'register_subject_group'
            );

            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'parent_subject_id' => $request->parent_subject_id,
                    'child_subject_id' => $request->child_subject_id
                ];

                $create = DBHelpers::create_query(Subject::class, $data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Subject added to group successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Subject could not be added to group, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['parent_subject_id', 'child_subject_id'];
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
        // delete a subject from a subject group (parent subject)
        if ($request->isMethod('delete')) {
            $validate = SubjectGroupValidators::validate_rules($request, 'delete_subject');

            if (!$validate->fails() && $validate->validated()) {
                $parent_subject_id = $request->parent_subject_id;
                $child_subject_id = $request->child_subject_id;

                // find the group where parent_subject_id and child_subject_id are available and then delete

                if (!DBHelpers::exists(SubjectGroup::class, ['parent_subject_id' => $parent_subject_id, 'child_subject_id' => $child_subject_id])) {
                    return ResponseHelper::error_response(
                        'Update failed, Subject not in group',
                        '',
                        401
                    );
                }

                // here it exists so we delete them

                $create = DBHelpers::delete_query_multi(Subject::class, ['parent_subject_id' => $parent_subject_id, 'child_subject_id' => $child_subject_id]);

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
