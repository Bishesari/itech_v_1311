<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'sms_ir' => [
        'url' => env('SMS_IR_URL'),
        'api_key' => env('SMS_IR_API_KEY'),

        'otp_template_id' => env('SMS_IR_OTP_TEMPLATE_ID'),
        'otp_parameter_name' => env('SMS_IR_OTP_PARAMETER_NAME', 'Code'),

        'password_template_id' => env('SMS_IR_PASSWORD_TEMPLATE_ID'),
        'password_username_parameter_name' => env('SMS_IR_PASSWORD_USERNAME_PARAMETER_NAME', 'USERNAME'),
        'password_parameter_name' => env('SMS_IR_PASSWORD_PARAMETER_NAME', 'PASSWORD'),
        'line_number' => env('SMS_IR_LINE_NUMBER'),
    ],

];
