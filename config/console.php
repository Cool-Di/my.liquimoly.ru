<?php

Yii::setAlias( '@tests', dirname( __DIR__ ) . '/tests/codeception' );

$params = require( __DIR__ . '/params.php' );
$db     = require( __DIR__ . '/db.php' );

$config = [ 'id' => 'basic-console',
            'basePath' => dirname( __DIR__ ),
            'bootstrap' => [ 'log' ],
            'controllerNamespace' => 'app\commands',
            'components' => [
                'authManager' => [ 'class' => 'yii\rbac\DbManager', ],
                'cache'       => [ 'class' => 'yii\caching\FileCache', ],
                'log'         => [ 'targets' => [ [ 'class' => 'yii\log\FileTarget', 'levels' => [ 'error', 'warning' ], ], ], ],
                'db'          => $db,
                'list'        => [ 'class' => 'app\components\ListComponent', ],
                ],
            'params' => $params,
            /*
            'controllerMap' => [
                'fixture' => [ // Fixture generation command line.
                    'class' => 'yii\faker\FixtureController',
                ],
            ],
            */ ];

if ( YII_ENV_DEV )
    {
    // configuration adjustments for 'dev' environment
    $config[ 'bootstrap' ][]      = 'gii';
    $config[ 'modules' ][ 'gii' ] = [ 'class' => 'yii\gii\Module', ];
    }

return $config;
