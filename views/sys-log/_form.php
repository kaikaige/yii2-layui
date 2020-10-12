<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SysLog */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="sys-log-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'=>[
            'class'=>'layui-form model-form',
            'lay-filter'=>'user-admin-filter',
            'id'=>'sys-log-form'
        ],
        'fieldConfig'=>[
            'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false]
        ]
    ]); ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'log_time')->textInput(['placeholder'=>'yyyy-MM-dd', 'class'=>'layui-input date-icon']) ?>

    <?= $form->field($model, 'prefix')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'context')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    layui.use(['layer', 'form', 'admin', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#sys-log-form').attr('action');
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            $.post(url, data.field, function() {
                top.layer.msg('添加成功', {icon: 1});
                admin.putTempData('t-sys-log-form-ok', true);  // 操作成功刷新表格
                // 关闭当前iframe弹出层
                admin.closeThisDialog();
            })
            return false;
        });
        laydate.render({elem: '#syslog-log_time'});
    });
</script>
