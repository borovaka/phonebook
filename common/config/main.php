<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.140.144;dbname=yii_test',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],

    ],

];
