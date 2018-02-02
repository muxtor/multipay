<?php
return [
    'sourceLanguage' => 'ru',
    'language' => 'ru_RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        ],
        'settings' => [
            'class' => 'common\modules\settings\Module',
        ],
    ],
    'components' => [
        'settings' => [
            'class' => 'common\modules\settings\components\Settings'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache'
        ],
        'i18n' => [
            'class' => Zelenin\yii\modules\I18n\components\I18N::className(),
            'languages' => ['ru-RU', 'en-EN', 'az-AZ']
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
        'mainsms' => [
            'class' =>'common\components\mainsms\MainSms',
            'projectName' => 'MultiPay',
            'apiKey' => '',
            // optional:
            'useSsl' => false,
            'testMode' => false,
        ],
            
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'common\components\LangUrlManager',
            'rules' => [
                [
                    'class' => 'common\components\UrlRouter'
                ],
                '<action>' => 'site/<action>',
                '<controller>/<action>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/*' => '<controller>/<action>',
            ],
        ],
    ],
];
