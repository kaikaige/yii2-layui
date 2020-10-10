<?php

namespace kaikaige\layui\models;

use Yii;
use kaikaige\layui\behaviors\DateBehavior;
/**
 * This is the model class for table "sys_config".
 *
 * @property int $id 主键
 * @property string $title 配置标题
 * @property string $name 配置标识
 * @property int $status 状态[0:禁用;1启用]
 * @property string $type 配置类型
 * @property int $cate_id 配置分类
 * @property string $extra 配置值
 * @property string $remark 配置说明
 * @property int $is_hide_remark 是否隐藏说明
 * @property string $default_value 默认配置
 * @property int $sort 排序
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 */
class SysConfig extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'cate_id', 'is_hide_remark', 'sort'], 'integer'],
            [['cate_id'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 30],
            [['extra', 'remark'], 'string', 'max' => 1000],
            [['default_value'], 'string', 'max' => 500],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '配置标题',
            'name' => '配置标识',
            'status' => '状态[0:禁用;1启用]',
            'type' => '配置类型',
            'cate_id' => '配置分类',
            'extra' => '配置值',
            'remark' => '配置说明',
            'is_hide_remark' => '是否隐藏说明',
            'default_value' => '默认配置',
            'sort' => '排序',
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
