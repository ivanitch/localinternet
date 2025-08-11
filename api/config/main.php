<?php

use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
);

return [
    'id' => 'app-api',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'   => FileTarget::class,
                    'levels'  => ['error'],
                    'logFile' => '@api/runtime/logs/error.log',
                ],
                [
                    'class'   => FileTarget::class,
                    'levels'  => ['warning'],
                    'logFile' => '@api/runtime/logs/warning.log',
                ],
                [
                    'class'   => FileTarget::class,
                    'levels'  => ['info'],
                    'logFile' => '@api/runtime/logs/info.log',
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET ' => 'v1/bank/index',
                'GET /v1/banks/<id:\d+>' => 'v1/bank/view',
                'PUT /v1/banks/<id:\d+>' => 'v1/bank/update',
                'DELETE /v1/banks/<id:\d+>' => 'v1/bank/delete',
            ],
        ],
    ],
    'params' => $params,
];
