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

class MarqueeValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'name' => 'required|unique:class_level',
            ],
            'update' => [
                'id' => 'required',
                'name' => 'required|unique:class_level',
            ],

            'activate' => [
                'id' => 'required',
            ],
            'deactivate' => [
                'id' => 'required',
            ],
            'deactivate_slug' => [
                'slug' => 'required',
            ],
            'activate_slug' => [
                'slug' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
