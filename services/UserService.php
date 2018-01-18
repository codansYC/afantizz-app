<?php
/**
 * Created by PhpStorm.
 * User: lekuai
 * Date: 2017/6/1
 * Time: 下午3:36
 */
namespace app\services;

use app\models\User;
use app\models\House;
use app\models\Collection;
use app\models\UserToken;
use app\utils\BizConsts;
use app\utils\GlobalAction;
use app\utils\UtilHelper;
use phpDocumentor\Reflection\Types\Array_;
use Yii;

class UserService
{

    static public function getUserByToken($token)
    {
        $user = User::find()
            ->where(['token' => $token])
            ->asArray()
            ->one();
        return $user;
    }

    static public function getUserByPhone($phone, $platform)
    {
        $user = User::find()
            ->where(['phone' => $phone])
            ->one();
        if (!$user) {
            $user = self::addUserWithPhone($phone, $platform);
        } else {
            $user->platform = $platform;
            $user->update_time = UtilHelper::getTimeStr("Y-m-d H:i:s");
            $user->save();
        }
        $userInfo = array('phone'=>$phone, 'token'=>self::getTokenWithUserId($user->user_id)->token);
        return $userInfo;
    }

    static private function addUserWithPhone($phone,$platform)
    {
        $user = new User();
        $user->phone = $phone;
        $user->user_id = 'afantizz'.UtilHelper::getGuid();
        $user->platform = $platform;
        $user->create_time = UtilHelper::getTimeStr("Y-m-d H:i:s");
        $user->save();
        self::generateUserToken($user->user_id, $phone);
        return $user;
    }

    static private function generateUserToken($userId, $phone) {
        $userToken = UserToken::find()->where(['user_id' => $userId])->one();
        $time = UtilHelper::getTimeStr("Y-m-d H:i:s");
        if ($userToken) {
            $userToken->update_time = $time;
        } else {
            $userToken = new UserToken();
            $userToken->create_time = $time;
            $userToken->user_id = $userId;
        }
        $userToken->token = UtilHelper::generateToken($userId, $phone);
        $userToken->save();
    }

    static private function getTokenWithUserId($userId) {
        $userToken = UserToken::find()
                        ->select('token')
                        ->where(['user_id' => $userId])
                        ->one();
        return $userToken;
    }

    static function getUserReleaseHouse($token)
    {
        $today = date("Y-m-d");
        $houses = House::find()->where(["token" => $token, "user_delete" => 0])
            ->orderBy("release_date DESC")
            ->asArray()
            ->all();
        foreach ($houses AS $index => $house) {
            $date = $house['release_date'];
            $houseId = $house['house_id'];
            $collection_count = Collection::find()->where(['house_id' => $houseId])
                                                  ->count();
            $houses[$index]['collection_count'] = $collection_count;
            if ($house['today'] == $today) {
                $houses[$index]['today_browse_count'] = $house['today_browse_count'];
            } else {
                $houses[$index]['today_browse_count'] = 0;
            }
            $houses[$index]['release_date'] = GlobalAction::computeTime($date);
            $houses[$index]['images'] = House::getThumbImages($house);
            $house['facilities'] = HouseService::getFacilities($house['house_id']);
        }
        return $houses;
    }

    static function getUserCollectionHouse($token)
    {
        $houseIds = Collection::find()->select('house_id')
                                      ->where(["token" => $token])
                                      ->orderBy('collection_date DESC')
                                      ->asArray()
                                      ->all();

        $houses = Array();
        foreach ($houseIds AS $houseId) {
            $house = House::find()->where(["house_id" => $houseId])
                                  ->asArray()
                                  ->one();
            $date = $house['release_date'];
            $house['release_date'] = GlobalAction::computeTime($date);
            $house['images'] = House::getThumbImages($house);
            $house['facilities'] = HouseService::getFacilities($houseId);
            array_push($houses,$house);
        }
        return $houses;

    }

}

