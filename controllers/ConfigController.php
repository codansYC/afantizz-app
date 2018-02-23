<?php
/**
 * Created by PhpStorm.
 * User: lekuai
 * Date: 2018/2/11
 * Time: 下午5:06
 */

namespace app\controllers;


use app\utils\BizConsts;
use app\utils\UtilHelper;
use Codeception\Module\Yii1;

class ConfigController extends BaseController
{
    public function actionConfig() {
        try {
            $districtArr = \Yii::$app->params['districts'];
            $districts = array();
            foreach (array_keys($districtArr) AS $code) {
                $district = [
                    "district_code" => $code,
                    "district_name" => $districtArr[$code]
                ];
                $districts[] = $district;
            }
            $city = [
                "city_code" => 021,
                "city_name" => "上海",
                "districts" => $districts
            ];
            $data = [
                "cities" => [$city],
                "subways" => \Yii::$app->params['subways'],
                "price_range_arr" => \Yii::$app->params['priceRanges']
            ];
            UtilHelper::echoResult(BizConsts::SUCCESS,BizConsts::SUCCESS_MSG,$data);
        } catch (\Exception $e) {
            UtilHelper::handleException($e);
        }
    }
}