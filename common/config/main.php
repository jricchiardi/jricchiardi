<?php
return [
	'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'utilcomponents' => [
            'class' => 'common\components\helpers\UtilComponents',
        ],
        'notificationscomponents' => [
            'class' => 'common\components\helpers\NotificationsComponents',
        ],
        'auditcomponents' => [
            'class' => 'common\components\helpers\AuditComponents',
        ],                
        'lockproductcomponents' => [
            'class' => 'common\components\helpers\LockProductComponents',
        ],
        'dashboardcomponent' => [
            'class' => 'common\components\helpers\DashBoardComponent',
        ],
//        'session' => [ 'class' => 'yii\web\Session',
//            'cookieParams' => ['httponly' => true, 'lifetime' => 1200],
//            'timeout' => 1200,
//            'useCookies' => true,
//            'name' => 'dow'
//        ],

        'i18n' => 
        [
            'translations' => 
            [
                'app' => 
                [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'menu' => 
                [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'kvexport' => 
                [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/kartik/yii2-export/messages',
                ],
            ],
        ],
	],
];
