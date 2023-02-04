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
            'dsn' => 'sqlsrv:Server=.\SQLEXPRESS;Database=dow.forecast',
            'username' => 'sa',
            'password' => '1qaz2wsx',
            'charset' => 'utf8',
        ],
      'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'admin@theseedguru.com',
                'password' => '!QAZ2wsx',
                'port' => '587',
                'encryption' => 'tls',
            ]
        ],
    ],
];
