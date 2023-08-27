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

class SubjectValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_subject' => [
                'name' => 'required|unique:subject',
                'subject_code' => 'required',
                'credit_unit' => 'required',
            ],

            'update_subject' => [
                'name' => 'required',
                'subject_code' => 'required',
                'credit_unit' => 'required',
                'id' => 'required',
            ],

            'delete_subject' => [
                'id' => 'required',
            ],

            "fetch_subject_by_id" => [
                'id' => 'required',
            ],

            "activate_subject" => [
                'id' => 'required',
            ]
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
