<?php

namespace App\Http\Controllers\Admin;
////namespace SendinBlue;

/////// require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Validations\SmtpSettingsValidator;
use App\Validations\ErrorValidation;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;
use App\Models\StmpSettings;
use SendinBlue;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\SmtpService;

class SmtpSettingsController extends Controller
{
    //

    public function send_smtp_mail_test()
    {
        //Set mail configuration
        SmtpService::setMailConfig();

        $data = ['name' => 'Virat Gandhi'];

        return Mail::send(['text' => 'mail'], $data, function ($message) {
            $message
                ->to('achawayne@gmail.com', 'Lorem Ipsum')
                ->subject('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com', $data['name']);
        });
    }

    public function send_mail_test()
    {
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            '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'
        );

        /// return 'hello';

        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
            new GuzzleHttp\Client(),
            $credentials
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
            'subject' => 'from the PHP SDK!',
            'sender' => [
                'name' => 'Sendinblue',
                'email' => 'contact@sendinblue.com',
            ],
            'replyTo' => [
                'name' => 'Sendinblue',
                'email' => 'contact@sendinblue.com',
            ],
            'to' => [
                ['name' => 'Max Mustermann', 'email' => 'achawayne@gmail.com'],
            ],
            'htmlContent' =>
                '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
            'params' => ['bodyMessage' => 'made just for you!'],
        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            print_r($result);
        } catch (Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
    }

    public function toggle(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('put')) {
            $validate = $val->validate_rules($request, 'toggle');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(StmpSettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Toggle failed,  SMTP Settings class not found',
                        '',
                        401
                    );
                }

                $current = DBHelpers::first_data(StmpSettings::class);
                $msg = '';

                if ($current->is_active == 1) {
                    $update = DBHelpers::update_query(
                        StmpSettings::class,
                        ['is_active' => 0],
                        $request->id
                    );
                    $msg = 'SMTP settings deactivated successfully';
                } else {
                    $update = DBHelpers::update_query(
                        StmpSettings::class,
                        ['is_active' => 1],
                        $request->id
                    );
                    $msg = 'SMTP settings activated successfully';
                }

                if (!$update) {
                    return ResponseHelper::error_response(
                        'SMTP toggle failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response($msg, null);
            } else {
                $errors = json_decode($validate->errors());
                $props = ['id'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }

    public function index()
    {
        $settings = DBHelpers::first_data(StmpSettings::class);
        return ResponseHelper::success_response(
            'SMTP settings fetched successfully',
            $settings
        );
    }

    public function update(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'update');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    !DBHelpers::exists(StmpSettings::class, [
                        'id' => $request->id,
                    ])
                ) {
                    return ResponseHelper::error_response(
                        'Update failed, School settings class not found',
                        '',
                        401
                    );
                }

                $update = DBHelpers::update_query_v2(
                    StmpSettings::class,
                    $request->only([
                        'hostname',
                        'protocol',
                        'username',
                        'password',
                        'created_by',
                    ]),
                    $request->id
                );

                if (!$update) {
                    return ResponseHelper::error_response(
                        'Update failed, Database updated issues',
                        '',
                        401
                    );
                }

                return ResponseHelper::success_response(
                    'Update was successfully',
                    null
                );
            } else {
                $errors = json_decode($validate->errors());
                $props = ['id', 'hostname', 'protocol', 'username', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }

    public function create(Request $request, SmtpSettingsValidator $val)
    {
        if ($request->isMethod('post')) {
            $validate = $val->validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {
                $settings = StmpSettings::query()->get();
                if (count($settings) > 0) {
                    return ResponseHelper::success_response(
                        'SMTP Settings added already, you can only update it',
                        null
                    );
                }

                $create = DBHelpers::create_query(
                    StmpSettings::class,
                    $request->all()
                );
                if ($create) {
                    return ResponseHelper::success_response(
                        'SMTP Settings added successfully',
                        null
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Registration failed, Database insertion issues',
                        '',
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['hostname', 'protocol', 'username', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);
                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }
}
