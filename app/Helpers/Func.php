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

    public static function school_session_builder($last = 10)
    {
        $yearly = [];
        $current_year = date('Y');
        $start_year = $current_year - $last;
        $next_year = $current_year + 1;
        $next_session = $current_year . '/' . $next_year;

        for ($i = 0; $i < $last; $i++) {
            $add = $i + 1;
            $sessions_years = $start_year + $i . '/' . $start_year + $add;
            array_push($yearly, $sessions_years);
        }
        array_push($yearly, $next_session);

        return $yearly;
    }
}
