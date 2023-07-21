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

class SchoolClassValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'name' => 'required|unique:school_class',
                'arm' => 'required',
                'staff' => 'required',
                'class_level' => 'required',
                'class_category' => 'required',
            ],

            'update' => [
                'name' => 'required',
                'arm' => 'required',
                'staff' => 'required',
                'id' => 'required',
                'class_level' => 'required',
                'class_category' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
