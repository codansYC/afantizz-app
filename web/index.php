<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$network=['\='];
preg_match('/HOSTNAME=(.*)/', file_get_contents('/etc/sysconfig/network'), $network);
echo file_get_contents('/etc/sysconfig/network');return;
var_dump($network);exit();
$hostname = explode('\=',$network[0]);
if($hostname[1] == 'iZ946wpz3z7Z' ){
    $_SERVER['RUNTIME_ENV'] = 'dev';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}
$hostname = bin2hex(gethostname());
$machine_id = base_convert($hostname,16,10) % 100;
defined('MACHINE_ID') or define('MACHINE_ID', $machine_id);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();