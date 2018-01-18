<?php

namespace app\controllers;

use app\utils\UtilHelper;
use app\utils\BizConsts;
use yii\web\Controller;

class TimeController extends Controller {
	// 关闭框架csrf验证
	public $enableCsrfValidation = false;
	// 关闭框架layout
	public $layout = false;
	
	public function actionTimestamp(){

		$requestParam = $_REQUEST;
		if(!\Yii::$app->request->isPost){
			UtilHelper::echoExitResult(BizConsts::REQUEST_INVALID_ERRCODE,BizConsts::REQUEST_INVALID_ERRMSG);
		}
		
		if (! isset ( $requestParam ['app_version'] ) || empty ( $requestParam ['app_version'] )) {
			UtilHelper::echoExitResult( BizConsts::PARAM_INVALID_ERRCODE, BizConsts::PARAM_INVALID_ERRMSG );
		}
		
		if (! isset ( $requestParam ['platform'] ) || empty ( $requestParam ['platform'] )) {
			UtilHelper::echoExitResult( BizConsts::PARAM_INVALID_ERRCODE, BizConsts::PARAM_INVALID_ERRMSG );
		}
		
		$appVersion = $requestParam['app_version'];
		$platform = $requestParam['platform'];
		$appkeys = json_decode(constant('APPKEY_'.strtoupper($platform)),true);
		if(!isset($appkeys[$appVersion])){
			UtilHelper::echoExitResult( BizConsts::PARAM_INVALID_ERRCODE, BizConsts::PARAM_INVALID_ERRMSG );
		}
		
		$appkey = $appkeys[$appVersion];
		
		$timestamp = $requestParam ['timestamp'];
		$signature = $requestParam ['signature'];
		$requestId = $requestParam ['request_id'];
		
		$arr = array (
				$appkey,
				$timestamp,
				$requestId
		);
		sort ( $arr, SORT_STRING );
		$dictStr = implode ( $arr );
		$encryptStr = sha1 ( $dictStr );
		
		// 签名合法性验证
		if ($signature != $encryptStr) {
			UtilHelper::echoExitResult( BizConsts::REQUEST_INVALID_ERRCODE, BizConsts::REQUEST_INVALID_ERRMSG );
		}

		$result['timestamp'] = strval(time());
	
		UtilHelper::echoExitResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$result);
	}
}

