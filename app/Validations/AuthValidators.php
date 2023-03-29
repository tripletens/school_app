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

class AuthValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        ///  return 'we here';

        self::$validation_rules = [
            'login' => [
                'email' => 'required',
                'password' => 'required',
            ],

            'reset_password' => [
                'password' => 'required',
                'otp' => 'required',
                'verify_password' => 'required|same:password',
            ],

            'register' => [
                'full_name' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ],

            'register_class' => [
                'name' => 'required',
                'arm' => 'required',
                'staff' => 'required',
            ],

            'register_staff' => [
                'first_name' => 'required',
                'other_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'phone' => 'required',
                'role' => 'required',
            ],

            'register_role' => [
                'name' => 'required|unique:role',
            ],

            'update_role' => [
                'name' => 'required|unique:role',
                'id' => 'required',
            ],
        ];

        ////return self::$validation_rules[$arg];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
