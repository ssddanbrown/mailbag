<?php

return [

    'hcaptcha' => [
        'active' => ! empty(env('HCAPTCHA_SITEKEY', null)),
        'sitekey' => env('HCAPTCHA_SITEKEY', null),
        'secretkey' => env('HCAPTCHA_SECRETKEY', null),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

];
