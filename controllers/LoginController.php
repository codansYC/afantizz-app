<?php
namespace app\controllers;

use app\models\ManagerDevice;
use app\services\CaptchaService;
use app\services\UserService;
use app\utils\UtilHelper;
use app\utils\BizConsts;
use app\utils\Sms;
use app\utils\SysConsts;

header("Access-Control-Allow-Origin: *"); # 跨域处理
class LoginController extends BaseController{

	/**
 * 获取验证码
 */
    public function actionCaptcha(){
        try{
            if (! isset ( $this->requestParam ['phone'] ) || empty ( $this->requestParam ['phone'] )) {
                UtilHelper::echoExitResult(BizConsts::PARAM_INVALID_ERRCODE, BizConsts::PARAM_INVALID_ERRMSG );
            }
            $phone = $this->requestParam['phone'];
            if(!UtilHelper::isPhone($phone)){
                UtilHelper::echoExitResult(BizConsts::INVALID_PHONE_ERRCODE,BizConsts::INVALID_PHONE_ERRMSG);
            }

            $captcha = CaptchaService::createCaptchaWithPhone($phone);
            if (BizConsts::APPLE_WHITELIST_PHONE == $phone) {
                UtilHelper::echoExitResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
                return;
            }

            /*这里有点问题  改为下面存数据库的方式
			RedisUtil::setCache(BizConsts::USER_LOGIN_VERIFY.$phone,$captcha,BizConsts::CAPTCHA_AVAILABLE);
            */

            // 发送动态验证码
            $sms = new Sms();
            $result = $sms->sendTemplateSMS($phone, [$captcha], BizConsts::SMS_VERIFY_TEMPLATE);

            if($result){
                if(isset($_SERVER['RUNTIME_ENV']) && $_SERVER['RUNTIME_ENV'] == 'dev' ){
                    UtilHelper::echoExitResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,array('captcha' => $captcha));
                }else{
                    UtilHelper::echoExitResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
                }
            }

            UtilHelper::echoExitResult(BizConsts::GET_CAPTCHA_ERRCODE,BizConsts::GET_CAPTCHA_ERRMSG);
        }catch (\Exception $e){
            UtilHelper::handleException($e);
        }
    }
	
	/**
	 * 登录接口
	 */
	public function actionLogin(){
		try {
			$phone = $this->requestParam['phone'];
			$captcha = $this->requestParam['captcha'];
            $platform = $this->requestParam['platform'];
            $valid = true;
			if(BizConsts::APPLE_WHITELIST_PHONE != $phone ){
				UtilHelper::validLogin($phone,$captcha);//校验手机号及验证码
                $valid = CaptchaService::checkPhoneAndCaptcha($phone,$captcha);
			}

            if (!$valid) {
                UtilHelper::echoExitResult(BizConsts::WRONG_CAPTCHA_ERRCODE,BizConsts::WRONG_CAPTCHA_ERRMSG);
            }
            $userInfo = UserService::getUserByPhone($phone,$platform);
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$userInfo);
		} catch (\Exception $e) {
			UtilHelper::handleException($e);
		}
	}
	/*
	 * 登出
	 */
	public function actionLogout(){
		try {
			$token = $this->requestParam['token'];
			$registrationId = isset($this->requestParam['registration_id']) ? $this->requestParam['registration_id'] : null;
            //插入loginout log（登出日志）
			$managerInfo = TokenService::checkToken($token,$type=false);
            $managerInfo['loginout_type'] = SysConsts::AVAILABLE_TWO;
            ManagerService::adduserloginoutlog($managerInfo,$token);
			ManagerDevice::logout($token, $registrationId);
			UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
		} catch (\Exception $e) {
			UtilHelper::handleException($e);
		}
	}
}