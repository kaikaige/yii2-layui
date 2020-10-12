<?php

namespace kaikaige\layui\models;

use kaikaige\layui\components\Configs;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "auth_menu".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $parent 父级
 * @property string $route 路由
 * @property int $order 序号
 * @property resource $data
 * @property string $icon 图标
 */
class AuthMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return Configs::instance()->menuTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'order'], 'integer'],
            [['data'], 'string'],
            [['name', 'icon'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent' => '父级',
            'route' => '路由',
            'order' => '权重',
            'data' => 'Data',
            'icon' => '图标',
        ];
    }

    public function getP()
    {
        return $this->hasOne(AuthMenu::class, ['id'=>'parent'])
            ->from(self::tableName() . ' AS p');;
    }

    public static function dropDwonList() {
        $models = AuthMenu::find()->orderBy(['order'=>SORT_ASC])->all();
        $data = [];
        /* @var $model AuthMenu*/
        foreach ($models as $model) {
            if ($model->parent == 0) {
                $data[] = [
                    'value' => $model->id,
                    'label' => $model->name,
                    'children' => []
                ];
            }
        }
        foreach ($models as $model) {
            if ($model->parent != 0) {
                foreach ($data as $key=>$p) {
                    if ($p['value'] == $model->parent) {
                        $data[$key]['children'][] = [
                            'value' => $model->id,
                            'label' => $model->name,
                        ];
                        break;
                    }
                }
            }
        }
        return Json::encode($data);
    }
}
