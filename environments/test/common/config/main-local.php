<?php


return [
    'language' => 'es-ES',
    'components' => [
        'urlManager' => [
            'scriptUrl' => 'http://bent03/testing.forecast/',
            'baseUrl' => 'http://bent03/testing.forecast/',
        ],
        'db' => [
            'class' => 'yii\db\Connection',            
            'dsn' => 'sqlsrv:Server=bent03;Database=testing.dow.forecast',
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
