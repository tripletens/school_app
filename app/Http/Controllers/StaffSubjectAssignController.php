<?php

namespace App\Http\Controllers;

use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\StaffSubjectAssign;
use App\Validations\ErrorValidation;
use App\Validations\StaffSubjectAssignValidators;
use Illuminate\Http\Request;

class StaffSubjectAssignController extends Controller
{
    //
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = StaffSubjectAssignValidators::validate_rules(
                $request,
                'register_staff_subject_assign'
            );

            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'subject_id' => $request->subject_id,
                    'staff_id' => $request->staff_id
                ];

                $create = DBHelpers::create_query(SubjectGroup::class, $data);

                if ($create) {
                    return ResponseHelper::success_response(
                        'Subject assigned to staff successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Subject could not be assigned to staff, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['subject_id', 'staff_id'];
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

    public function destroy(Request $request)
    {
        // delete a subject from a class
        if ($request->isMethod('delete')) {
            $validate = StaffSubjectAssignValidators::validate_rules($request, 'delete_subject_group');

            if (!$validate->fails() && $validate->validated()) {
                $subject_id = $request->subject_id;
                $staff_id = $request->staff_id;

                // find the class where staff_id and subject_id are available and then delete

                if (!DBHelpers::exists(StaffSubjectAssign::class, ['subject_id' => $subject_id, 'staff_id' => $staff_id])) {
                    return ResponseHelper::error_response(
                        'Update failed, Subject not assigned to staff',
                        '',
                        401
                    );
                }

                // here it exists so we delete them

                $delete = DBHelpers::delete_query_multi(StaffSubjectAssign::class, ['subject_id' => $subject_id, 'staff_id' => $staff_id]);

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
