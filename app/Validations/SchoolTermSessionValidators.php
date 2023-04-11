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

class SchoolTermSessionValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_term' => [
                'name' => 'required|unique:school_term_session',
            ],

            'update_term' => [
                'name' => 'required|unique:school_term_session',
                'id' => 'required',
            ],

            'delete_term' => [
                'id' => 'required',
            ],
            'activate_term' => [
                'id' => 'required',
            ],
        ];
        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
