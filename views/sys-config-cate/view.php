<?php

use yii\widgets\DetailView;
use kaikaige\layui\models\menus\StatusMenu;

/* @var $this yii\web\View */
/* @var $model kaikaige\layui\models\SysConfigCate */
?>
<div class="layui-fluid sys-config-cate-view">
    <?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'layui-table','lay-even'=>'','lay-size'=>'sm'],
    'attributes' => [
        'id',
        'title',
        'sort',
        ['attribute'=>'status', 'value'=>function($model) {
            return StatusMenu::$statusList[$model->status];
        }],
        'create_time',
        'update_time',
    ],
    ]) ?>
</div>
