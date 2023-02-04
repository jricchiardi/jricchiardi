<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
       'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'request' => [

            'cookieValidationKey' => 'LSZVaQI3uPbvn9c1PMiC9YcS4PeglJv2',
        ],
    ],
];
