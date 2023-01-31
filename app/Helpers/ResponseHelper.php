<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function status_code()
    {
        $status_code = [
            '200' => 'success',
            '202' => 'accepted',
            '203' => 'Non-Authoritative Information',
            '204' => 'No Content',
            '400' => 'Bad Request',
            '401' => 'Unauthorised',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
        ];
    }

    public static function error_response($msg = null, $data = null, $code = 0)
    {
        return (object) [
            'status' => false,
            'success' => false,
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
            'debug' => [],
        ];
    }

    public static function success_response(
        $msg = null,
        $data = null,
        $token = null
    ) {
        return (object) [
            'status' => true,
            'success' => true,
            'code' => 200,
            'data' => $data,
            'token' => $token,
            'msg' => $msg,
            'debug' => [],
        ];
    }

    public static function serverError($error, int $code = 500)
    {
        return response()->json(
            [
                'status' => false,
                'meta_data' => [
                    'data' => null,
                    'status_code' => $code,
                    'response' =>
                        'Oops, server error... Reach out to the backend engineers',
                ],
                'debug' => env('APP_DEBUG') ? $error->getMessage() : null,
            ],
            $code
        );
    }
}
