<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'language'=>'ru-RU',
    'name' => 'API App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\api\v1\Module',
        ],
        'backend' => [
            'class' => 'app\modules\backend\Module',
            'controllerNamespace' => 'app\controllers\backend',
            'viewPath' => '@app/views/backend',
            'layout' => '@app/views/layouts/backend',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'controllerNamespace' => 'app\controllers\user',
            'viewPath' => '@app/views/user',
        ],  
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'fbY5Ay9Hdfkrl9GHx089hEQ',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'backend',
                    'routePrefix' => 'backend',
                    'rules' => [
                        '' => 'default/index',
                    ],
                ],
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'api/v1',
                    'routePrefix' => 'v1',
                    'rules' => [
                        '' => 'default/index',
                        '<controller:\w+>' => '<controller>/index',
                        'post/<id:\d+>' => 'post',
                    ],
                ],                
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'user',
                    'routePrefix' => 'user',
                    'rules' => [
                        '' => 'default/index',
                    ],
                ],                
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
