<?php

/**
 *
 *
 * @package
 * @author	School Mgt
 * @copyright
 * @version	1.0.0
 */

namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Func
{
    public static function run_validation(Request $request, array $input)
    {
        return Validator::make($request->all(), $input);
    }
}
