<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model kaikaige\layui\models\SysConfig */
?>
<div class="layui-fluid sys-config-view">
    <?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'layui-table','lay-even'=>'','lay-size'=>'sm'],
    'attributes' => [
              'id',
          'title',
          'name',
          'status',
          'type',
          'cate_id',
          'extra',
          'remark',
          'is_hide_remark',
          'default_value',
          'sort',
          'create_time',
          'update_time',
    ],
    ]) ?>
</div>
