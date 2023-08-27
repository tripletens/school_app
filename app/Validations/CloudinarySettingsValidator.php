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

class CloudinarySettingsValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'name' => 'required',
                'api_key' => 'required',
                'secret_key' => 'required',
                'cloudinary_url' => 'required',
            ],
            'update' => [
                'id' => 'required',
                'name' => 'required',
                'api_key' => 'required',
                'secret_key' => 'required',
                'cloudinary_url' => 'required',
            ],
            'toggle' => [
                'id' => 'required',
            ],
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
