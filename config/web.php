<?php

use developeruz\db_rbac\behaviors\AccessBehavior;
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'ru',
    'name' => 'Liqui Moly',
//    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'permit' => [
            'class' => 'developeruz\db_rbac\Yii2DbRbac',
            'params' => [
                'userClass' => 'app\models\User'
            ]
        ],
    ],
	'as AccessBehavior' => [
        'class' => AccessBehavior::className(),
        'rules' =>
            [
            'site' =>
                [
                    [
                        'actions' => ['login', 'index', 'logout', 'error', 'planogram'],
                        'allow' => true,
                    ]
                ],
/*
            'akcii' =>
                [
                    [
                        'actions' => ['archiv', 'view_arch', 'index', 'view'],
                        'allow' => true,
                    ],
                ],
            'prezentacii' =>
            	[
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
            	],
            'search' =>
            	[
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
            	],
            'catalog' =>
            	[
                    [
                        'actions' => ['index', 'other', 'adv', 'item'],
                    ],
            	],
            'video' =>
            	[
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
            	],
            'meropriyatiya' =>
            	[
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
            	],
            'ajax' =>
            	[
                    [
                        'actions' => ['index', 'getfasovki', 'addbasket', 'updatebasket'],
                        'allow' => true,
                    ],
            	],
            'vebinar' =>
            	[
                    [
                        'actions' => ['index', 'view_arch'],
                        'allow' => true,
                    ],
            	],
            'uchebniki' =>
            	[
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
            	],
            'news' =>
                [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true
                    ],
                ],
            'feedback' =>
                [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true
                    ],
                ],
            'downloads' =>
                [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true
                    ],
                ],
            'orderhistory' =>
            	[
            		[
                        'actions' => ['index', 'show', 'cancel', 'copy'],
                        'allow' => true
              		]
            	],
            'basket' =>
            	[
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true
                    ],
            	],
*/
            'permit/access' =>
            	[
            	 	[
                        'actions' => ['role', 'permission', 'add-permission', 'add-role', 'update-permission', 'update-role'],
                        'allow' => true,
                        'roles' => ['root'],
            	 	]
            	]
            ]
	],
    'components' => [
		'sphinx' => [
			'class' => 'yii\sphinx\Connection',
			'dsn' => 'mysql:host=127.0.0.1;port=3313;',
		],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wV7PnOIs-BER24HoKrgi6EMYk0XAihS6',
        ],
/*		'view' => [
			'theme' => [
				'pathMap' => [
					'@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
				],
			],
		],*/
       	'session' => [
           	'name' => 'PHPBACKSESSID',
            'savePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tmp',
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '192.168.0.5',
//                'username' => 'username@gmail.com',
//                'password' => 'password',
                'port' => '25',
//                'encryption' => 'tls',
            ]
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
            	'obuchenie/prezentacii' => 'prezentacii',
            	'obuchenie/video' => 'video',
            	'obuchenie/vebinar/' => 'vebinar',
            	'obuchenie/vebinar/view_arch' => 'vebinar/view_arch',
            	'obuchenie/uchebniki' => 'uchebniki',
            	'ordershow/show/<order_id:\d+>' => '/ordershow/show',
            	'ordershow/<user_id:\d+>' => '/ordershow',
            	'clients/changepasswd/<user_id:\d+>' => '/clients/changepasswd',
				'catalog/item/<item_id:[A-Za-z0-9_-А-Яа-я]+>' => '/catalog/item',
            	'orderhistory/show/<order_id:\d+>' => '/orderhistory/show',
            	'orderhistory/cancel/<order_id:\d+>' => '/orderhistory/cancel',
            	'orderhistory/copy/<order_id:\d+>' => '/orderhistory/copy',
            	'orderhistory/copybasket/<order_id:\d+>' => '/orderhistory/copybasket',
            	'orderhistory/download/<order_id:\d+>' => '/orderhistory/download',
            	'catalog/index/<category_0:[A-Za-z0-9_-]+>' => '/catalog/index',
            	'catalog/index/<category_0:[A-Za-z0-9_-]+>/<category_1:[A-Za-z0-9_-]+>' => '/catalog/index',
            	'catalog/index/<category_0:[A-Za-z0-9_-]+>/<category_1:[A-Za-z0-9_-]+>/<category_2:[A-Za-z0-9_-]+>' => '/catalog/index',
            	'catalog/index/<category_0:[A-Za-z0-9_-]+>/<category_1:[A-Za-z0-9_-]+>/<category_2:[A-Za-z0-9_-]+>/<category_3:[A-Za-z0-9_-]+>' => '/catalog/index',
            	'catalog/index/<category_0:[A-Za-z0-9_-]+>/<category_1:[A-Za-z0-9_-]+>/<category_2:[A-Za-z0-9_-]+>/<category_3:[A-Za-z0-9_-]+>/<category_4:[A-Za-z0-9_-]+>' => '/catalog/index'
            ],
        ],
        'list' => [
            'class' => 'app\components\ListComponent'
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'HH:mm',
            'datetimeFormat' => 'dd.MM.yyyy / HH:mm',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
//            'timeZone' => 'Europe/Moscow',
            'defaultTimeZone' => 'UTC'
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
