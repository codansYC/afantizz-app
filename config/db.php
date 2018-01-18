<?php

$pro = false;

if (!$pro) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=afantizz',
        'username' => 'root',
        'password' => '',
    ];
}
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=afantizz',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8',
];

