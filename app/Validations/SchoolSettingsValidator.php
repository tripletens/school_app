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

class SchoolSettingsValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'running_year' => 'required',
            ],
            'update' => [
                'id' => 'required',
                'running_year' => 'required',
            ],
            'active' => [
                'id' => 'required',
            ],
            'personification' => [
                'id' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
