<?php
if (YII_ENV_DEV || YII_ENV_TEST) { // 测试环境
	defined ( 'APPKEY_ANDROID' ) or define ( 'APPKEY_ANDROID', json_encode(array(
		'1.0.0' => 'EqBAYzVRw2k7FswL',
        '1.1.0' => 'testCcmsIam500QiangA',
	)));
	defined ( 'APPKEY_IOS' ) or define ( 'APPKEY_IOS', json_encode(array(
		'1.0.0' => 'EqBAYzVRw2k7FswL',
        '1.0.1' => 'EqBAYzVRw2k7FswL',
        '1.1.0' => 'testCcmsIam500QiangA',
        '1.1.1' => 'testCcmsIam500QiangA',
        '1.1.2' => 'testCcmsIam500QiangA',
	)));
} else if(YII_ENV_PROD){ // 正式环境
	defined ( 'APPKEY_ANDROID' ) or define ( 'APPKEY_ANDROID', json_encode(array(
		'1.0.0' => 'EqBAYzVRw2k7FswL',
        '1.1.0' => 'NkIORDjp910QbITJRg1G',
	)));
	defined ( 'APPKEY_IOS' ) or define ( 'APPKEY_IOS', json_encode(array(
		'1.0.0' => 'EqBAYzVRw2k7FswL',
        '1.1.0' => 'NPuTBXudmmdXe1Lt3pR9',
        '1.1.1' => 'NPuTBXudmmdXe1Lt3pR9',
        '1.1.2' => 'NPuTBXudmmdXe1Lt3pR9',
	)));

}

defined('API_VERSION_ANDROID') or define('API_VERSION_ANDROID', json_encode(array(
	'1.0.0' => 'v1',
    '1.1.0' => 'v1'
)));

defined('API_VERSION_IOS') or define('API_VERSION_IOS', json_encode(array(
	'1.0.0' => 'v1',
	'1.1.0' => 'v1',
	'1.1.1' => 'v1',
    '1.1.2' => 'v1'
)));

defined('APP_VERSION_ANDROID') or define('APP_VERSION_ANDROID', json_encode(array(
	'1.0.0' => 100,
	'1.1.0' => 110
)));

defined('APP_VERSION_IOS') or define('APP_VERSION_IOS', json_encode(array(
	'1.0.0' => 100,
    '1.1.0' => 110,
    '1.1.1' => 111,
    '1.1.2' => 112
)));

defined('IOS_LATEST_VERSION') or define('IOS_LATEST_VERSION','1.1.2');

defined('ANDROID_LATEST_VERSION') or define('ANDROID_LATEST_VERSION','1.1.0');

defined('COMMERCIAL_PHONE') or define('COMMERCIAL_PHONE','021-61318123');

defined('LOCK_PATH') or define('LOCK_PATH', dirname(__FILE__).'/../../logs/lock/');
