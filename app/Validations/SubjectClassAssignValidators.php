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

class SubjectClassAssignValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_subject_class_assign' => [
                'subject_id' => 'required',
                'class_id' => 'required',
            ],

            'delete_subject' => [
                'subject_id' => 'required',
                'class_id' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
