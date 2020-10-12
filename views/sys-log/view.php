<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SysLog */
?>
<div class="layui-fluid sys-log-view">
    <?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'layui-table','lay-even'=>'','lay-size'=>'sm'],
    'attributes' => [
              'id',
          'level',
          'category',
          'log_time',
          'prefix:ntext',
          'message:ntext',
          'context:ntext',
          'ip',
    ],
    ]) ?>
</div>
