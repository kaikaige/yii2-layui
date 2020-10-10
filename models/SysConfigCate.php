<?php

namespace kaikaige\layui\models;

use Yii;
use kaikaige\layui\behaviors\DateBehavior;
/**
 * This is the model class for table "sys_config_cate".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 */
class SysConfigCate extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_config_cate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '标题',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }
	public function behaviors() {
        return [
            ['class'=>DateBehavior::class]
        ];
    }
    
}
