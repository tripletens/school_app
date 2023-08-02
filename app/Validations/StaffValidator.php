<?php
/**
 *
 *
 * @category Validations
 * @author	School Mgt Portal
 * @copyright Copyright (c) 2023. All right reserved
 * @version	1.0
 */

namespace App\Validations;
use App\Helpers\Func;

class StaffValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_staff' => [
                'first_name' => 'required',
                'other_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required',
                'role' => 'required',
            ],

            'staff' => [
                'uid' => 'required',
            ],

            'update_staff' => [
                'first_name' => 'required',
                'other_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required',
                'role' => 'required',
                'uid' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
