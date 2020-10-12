<?php

namespace kaikaige\layui\models;

use Yii;
/**
 * This is the model class for table "log_backend".
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property string $log_time
 * @property string $prefix
 * @property string $message
 * @property string $context
 * @property string $ip
 */
class SysLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $sys = f_get('sys', 'backend');
        return 'log_'.$sys;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'safe'],
            [['prefix', 'message', 'context'], 'string'],
            [['category', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'category' => 'Category',
            'log_time' => 'Log Time',
            'prefix' => 'Prefix',
            'message' => 'Message',
            'context' => 'Context',
            'ip' => 'Ip',
        ];
    }
    
}
