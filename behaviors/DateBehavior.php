<?php
namespace kaikaige\layui\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
/**
 * 日期修改
 * @author gaokai
 *
 */
class DateBehavior extends Behavior {

    public $createAttribute = 'create_time';

    public $updateAttribute = 'update_time';

    public function events() {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }


    public function beforeInsert() {
        $model = $this->owner;
        $create_attr = $this->createAttribute;
        $update_attr = $this->updateAttribute;
        $model->hasAttribute($create_attr) && $model->$create_attr = date('Y-m-d H:i:s');
        $model->hasAttribute($update_attr) && $model->$update_attr = date('Y-m-d H:i:s');
    }

    public function beforeUpdate() {
        $model = $this->owner;
        $update_attr = $this->updateAttribute;
        $model->hasAttribute($update_attr) && $model->$update_attr = date('Y-m-d H:i:s');
    }
}