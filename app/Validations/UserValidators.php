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

class UserValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'login' => [
                'email' => 'required',
                'password' => 'required',
            ],

            'user_register' => [
                'role' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
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

            'register_staff' => [
                'first_name' => 'required',
                'other_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'phone' => 'required',
                'role' => 'required',
            ],

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
