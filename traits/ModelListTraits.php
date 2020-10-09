<?php
namespace kaikaige\layui\traits;

use common\models\enums\IsDeleteEnum;
use yii\helpers\ArrayHelper;

trait ModelListTraits
{
    private static $_list;

    public static function select($index=null) {
        if (self::$_list === null) {
            $models = self::find()
                ->where(['is_deleted'=>IsDeleteEnum::NO])
                ->asArray()
                ->all();
            self::$_list = ArrayHelper::map($models, 'id', 'name');
        }
        if ($index !== null) {
            return isset(self::$_list[$index]) ? self::$_list[$index] : '(未设置)';
        }
        return self::$_list;
    }
}