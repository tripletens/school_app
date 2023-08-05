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

class NewsletterValidators
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'register_newsletter' => [
                'title' => 'required|unique:newsletter',
                'description' => 'required',
            ],

            'update_newsletter' => [
                'title' => 'required',
                'description' => 'required',
                'id' => 'required',
            ],

            'delete_newsletter' => [
                'id' => 'required',
            ],

            "fetch_newsletter_by_id" => [
                'id' => 'required',
            ]
        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
