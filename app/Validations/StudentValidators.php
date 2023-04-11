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

class StudentValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_student' => [
                'first_name' => 'required',
                'other_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'phone' => 'required',
                'role' => 'required',
                'dob' => 'required',
                'sport_house' => 'required',
                'admission_number' => 'required',
                'school_class' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
