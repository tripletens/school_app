<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    //

    protected static $user;
    protected static $uid;

    public function __construct()
    {
        if (!JWTAuth::getToken()) {
            //  return 'no token';

            ///  throw new JWTException('Token not provided');
        }

        // self::$uid = JWTAuth::parseToken()->authenticate()->id;
    }

    public function dashboard()
    {
        return 'heyye';
    }
}
