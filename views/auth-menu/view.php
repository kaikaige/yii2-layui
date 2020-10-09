<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model kaikaige\layui\models\AuthMenu */
?>
<div class="layui-fluid auth-menu-view">
    <?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'layui-table','lay-even'=>'','lay-size'=>'sm'],
    'attributes' => [
              'id',
          'name',
          'parent',
          'route',
          'order',
          'data',
          'icon',
    ],
    ]) ?>
</div>
