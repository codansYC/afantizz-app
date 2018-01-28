<?php
/**
 * Created by PhpStorm.
 * User: lekuai
 * Date: 2017/6/18
 * Time: 下午7:43
 */

namespace app\controllers;

use app\models\Feedback;
use app\services\UserService;
use app\utils\GlobalAction;
use app\utils\BizConsts;
use app\utils\UtilHelper;

class FeedbackController extends BaseController
{
    function actionFeedback() {
        try {
            $content = $this->requestParam['content'];
            $date = UtilHelper::getTimeStr("Y-m-d H:i:s");
            if (!isset($content) || $content == '') {
                UtilHelper::echoExitResult(BizConsts::FEEDBACK_INPUT_ERRCODE,BizConsts::FEEDBACK_INPUT_ERRMSG);
            }

            $feedback = new Feedback();

            if (isset($this->requestParam['token'])) {
                $userId = UserService::getUserIdByToken($this->requestParam['token']);
                if ($userId) {
                    $feedback->user_id = $userId;
                }
            }
            $feedback->content = $content;
            $feedback->create_time = $date;
            $feedback->save();
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG);
        } catch (\Exception $e) {
            UtilHelper::handleException($e);
        }
    }

}