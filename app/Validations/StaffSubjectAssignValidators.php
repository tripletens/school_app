<?php
/**
 *
 *
 * @category Validations
 * @author	Champa
 * @copyright Copyright (c) 2022. All right reserved
 * @version	1.0
 */

namespace App\Validations;
use App\Helpers\Func;

class StaffSubjectAssignValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_staff_subject_assign' => [
                'subject_id' => 'required',
                'staff_id' => 'required',
            ],

            'update_subject_assign' => [
                'subject_id' => 'required',
                'staff_id' => 'required',
            ],

            'delete_subject_assign' => [
                'subject_id' => 'required',
                'staff_id' => 'required',
            ],

            "fetch_subject_by_id" => [
                'id' => 'required',
            ]
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
