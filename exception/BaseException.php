<?php
namespace  app\exception;

class BaseException extends \Exception { 
	public function __construct($message,$code){
// 		Logger::info($message);
		parent::__construct($message,$code);
	}

    const PARAM_INVALID_ERRMSG = "参数无效";
    const PARAM_INVALID_ERRCODE = 10000;

    const REQUEST_TIMEOUT_ERRMSG = "请求超时";
    const REQUEST_TIMEOUT_ERRCODE = 10001;
}
