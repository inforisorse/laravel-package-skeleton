<?php

// config for Inforisorse/SmsGateway
return [
    'default' => [
        'driver' => 'skebby',
    ],
    'drivers' => [
        'skebby' => [
            'driverClass' => Inforisorse\SmsGateway\Drivers\Skebby::class,
            'apiBaseUrl' => 'https:api.skebby.it/API/v1.0/REST/',
            'authtype' => 'token',
            'auth' => [
                'token' => '6ubgRkZAaISX3csjBwgoXM1S', // set API token for use qith 'token' authtype
                'login' => [
                    'user' => '',   // set username if using 'login' authtype
                    'password' => '', // set API password if using 'login' authtype
                ],
            ],

        ],
    ],
];
