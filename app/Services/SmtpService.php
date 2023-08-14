<?php

namespace App\Services;

use App\Models\StmpSettings;
use App\Helpers\ResponseHelper;

class SmtpService
{
    public static function setMailConfig()
    {
        //Get the data from settings table
        $settings = StmpSettings::query()
            ->get()
            ->first();

        if (!$settings) {
            return ResponseHelper::error_response(
                'No Smtp Settings found',
                '',
                404
            );
        }

        //Set the data in an array variable from settings table
        $mailConfig = [
            'transport' => 'smtp',
            'host' => $settings->hostname,
            'port' => $settings->port,
            'encryption' => $settings->protocol,
            'username' => $settings->username,
            'password' => $settings->password,
            'timeout' => null,
        ];

        //To set configuration values at runtime, pass an array to the config helper
        config(['mail.mailers.smtp' => $mailConfig]);
    }
}

?>
