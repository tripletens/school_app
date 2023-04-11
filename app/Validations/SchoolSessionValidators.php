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

class SchoolSessionValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_school_session' => [
                'school_session' => 'required',
                'school_term' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'day_duration' => 'required',
                'school_population' => 'required',
            ],
        ];
        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
