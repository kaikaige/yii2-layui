<?php

/* @var $this yii\web\View */
/* @var $model common\models\GoodsType */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
    $form = \common\layui\form\ActiveForm::begin([
    'model' => $model,
    'id' => 'searchForm'
]); ?>

<div class="layui-inline layui-col-md6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
</div>

<div class="layui-inline layui-col-md6">
</div>
<div class="layui-form-item text-right">
    <button class="layui-btn layui-btn-sm" lay-filter="<?= $model->formName()?>Submit" lay-submit>搜索</button>
</div>

<?php \common\layui\form\ActiveForm::end(); ?>
