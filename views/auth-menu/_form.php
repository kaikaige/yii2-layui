<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model kaikaige\layui\models\AuthMenu */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="auth-menu-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'=>[
            'class'=>'layui-form model-form',
            'lay-filter'=>'user-admin-filter',
            'id'=>'auth-menu-form'
        ],
        'fieldConfig'=>[
            'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false]
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if (Yii::$app->request->get('type') != 'system') {?>
    <?= $form->field($model, 'parent')->textInput() ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
    <?php }?>
    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    layui.use(['layer', 'form', 'admin', 'laydate', 'cascader'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#auth-menu-form').attr('action');
        var cascader = layui.cascader;

        cascader.render({
            elem: '#authmenu-parent',
            changeOnSelect: true,
            // data: [{"value":1,"label":"业务管理"},{"value":2,"label":"订单管理"},{"value":3,"label":"系统管理"},{"value":5,"label":"用户管理"},{"value":15,"label":"test233"}],
            data: <?= \kaikaige\layui\models\AuthMenu::dropDwonList() ?>,
            //reqData: function (values, callback, data) {
            //    var id = 0
            //    if (data) {
            //        id = data.value
            //    }
            //    // values是当前所有选中的值，data是当前选中的对象
            //    $.get('<?//= \yii\helpers\Url::to(['list']) ?>//', {parent:id}, function(res){
            //        callback(res);  // 数据请求完成通过callback回调
            //    },'json');
            //},
            onChange(values, data) {
                $("#authmenu-parent").val(data.value)
            }
        })
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            $.post(url, data.field, function() {
                top.layer.msg('添加成功', {icon: 1});
                admin.putTempData('t-auth-menu-form-ok', true);  // 操作成功刷新表格
                // 关闭当前iframe弹出层
                admin.closeThisDialog();
            })
            return false;
        });
    });
</script>
