<?php
namespace kaikaige\layui\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
/**
 * 
 *  密码修改行为
 * @author gaokai
 *
 */
class PasswordBehavior extends Behavior {
	
	public $attribute = 'password';
	
	public function events() {
		return [
			BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
		];
	}
	
	
	public function beforeInsert() {
		$model = $this->owner;
		$attr = $this->attribute;
		$model->$attr = md5($model->$attr);
	}
	
	public function beforeUpdate() {
		$model = $this->owner;
		$attr = $this->attribute;
		if ($model->oldAttributes[$attr] != $model->$attr) {
			$model->$attr = md5($model->$attr);
		}
	}
}