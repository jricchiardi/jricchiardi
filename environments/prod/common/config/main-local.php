<?php


return [
    'language' => 'es-ES',
    'components' => [
        'urlManager' => [
            'scriptUrl' => 'http://localhost/dow.forecast/frontend/web/',
            'baseUrl' => 'http://localhost/dow.forecast/frontend/web/',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:Server=bent03;Database=dow.forecast',
            'username' => 'dow.forecast',
            'password' => 'MJmc1960',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'outlook.046d.mgd.msft.net',
            ],
        ],
    ],
];
