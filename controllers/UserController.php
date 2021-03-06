<?php
/**
 * Created by PhpStorm.
 * User: lekuai
 * Date: 2017/6/1
 * Time: 下午4:00
 */

namespace app\controllers;

use app\services\UserService;
use app\utils\UtilHelper;
use app\utils\BizConsts;

class UserController extends BaseController{

    function actionInfo() {
        try{
            $token = $this->requestParam['token'];
            $user = UserService::getUserByToken($token);
            if (!$user) {
                UtilHelper::echoExitResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
            }
            $userInfo = ['phone' => $user->phone, 'wx' => $user->wx];
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$userInfo);
        }catch (\Exception $e){
            UtilHelper::handleException($e);
        }
    }

    function actionReleaseList() {
        try{
            $userId = $this->getUserIdByParam($this->requestParam);
            if (!$userId) {
                UtilHelper::echoExitResult(BizConsts::UNLOGIN_ERRCODE,BizConsts::UNLOGIN_ERRMSG);
            }
            $data = UserService::getUserReleaseHouse($userId);
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$data);
        }catch (\Exception $e){
            UtilHelper::handleException($e);
        }
    }

    function actionFollowList() {
        try{
            $userId = $this->getUserIdByParam($this->requestParam);
            if (!$userId) {
                UtilHelper::echoExitResult(BizConsts::UNLOGIN_ERRCODE,BizConsts::UNLOGIN_ERRMSG);
            }
            $data = UserService::getUserFollowHouse($userId);
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$data);
        }catch (\Exception $e){
            UtilHelper::handleException($e);
        }

    }

    function actionFollow() {
        $userId = $this->getUserIdByParam($this->requestParam);
        if (!$userId) {
            UtilHelper::echoExitResult(BizConsts::UNLOGIN_ERRCODE,BizConsts::UNLOGIN_ERRMSG);
        }
        $house_id = $this->requestParam['house_id'];
        UserService::followHouse($house_id,$userId);
        UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
    }

    function actionCancelFollow() {
        $userId = $this->getUserIdByParam($this->requestParam);
        if (!$userId) {
            UtilHelper::echoExitResult(BizConsts::UNLOGIN_ERRCODE,BizConsts::UNLOGIN_ERRMSG);
        }
        $house_id = $this->requestParam['house_id'];
        UserService::cancelFollowHouse($house_id,$userId);
        UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
    }

    function getUserIdByParam($param) {
        $token = $param['token'];
        if (empty($token)) {
            return null;
        }
        $userId = UserService::getUserIdByToken($token);
        return $userId;
    }

}
