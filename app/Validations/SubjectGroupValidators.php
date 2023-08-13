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

class SubjectGroupValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_subject_group' => [
                'parent_subject_id' => 'required',
                'child_subject_id' => 'required',
            ],

            'update_subject_group' => [
                'parent_subject_id' => 'required',
                'child_subject_id' => 'required',
                'id' => 'required',
            ],

            'delete_subject_group' => [
                'parent_subject_id' => 'required',
                'child_subject_id' => 'required'
            ],

            "fetch_subject_by_id" => [
                'id' => 'required',
            ]
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
