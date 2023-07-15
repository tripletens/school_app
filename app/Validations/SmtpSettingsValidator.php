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

class SmtpSettingsValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'hostname' => 'required',
                'protocol' => 'required',
                'username' => 'required',
                'password' => 'required',
            ],
            'update' => [
                'id' => 'required',
                'hostname' => 'required',
                'protocol' => 'required',
                'username' => 'required',
                'password' => 'required',
            ],
            'toggle' => [
                'id' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
