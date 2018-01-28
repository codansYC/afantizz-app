<?php
/**
 * Created by PhpStorm.
 * User: lekuai
 * Date: 2017/6/15
 * Time: 下午6:59
 */

namespace app\models;

use yii\db\ActiveRecord;

class Image extends ActiveRecord {

    public static function tableName()
    {
        return 'image';
    }

}
