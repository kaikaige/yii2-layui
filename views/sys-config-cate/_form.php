<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model kaikaige\layui\models\SysConfigCate */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="sys-config-cate-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'=>[
            'class'=>'layui-form model-form ',
            'lay-filter'=>'user-admin-filter',
            'id'=>'sys-config-cate-form',
        ],
        'fieldConfig'=>[
            'template' => '<div class="layui-form-item layui-inline">{label}<div class="layui-input-block">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false]
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'可用', 0=>'禁用']) ?>
    <div class="layui-form-item" style="position: relative;">
        <label class="layui-form-label">配置列表：</label>
        <div class="layui-input-block">
            <table id="configTable" lay-filter="configTable"></table>
        </div>
        <button class="layui-btn layui-btn-sm icon-btn" id="demoEDBtnAddComment"
                style="position: absolute; left: 20px;top: 60px;padding: 0 5px;" type="button">
            <i class="layui-icon">&#xe654;</i>添加项目
        </button>
    </div>
    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- 表格操作列 -->
<script type="text/html" id="sys-config-table-bar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="details">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['layer', 'form', 'table', 'admin', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#sys-config-cate-form').attr('action');
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            $.post(url, data.field, function() {
                top.layer.msg('添加成功', {icon: 1});
                admin.putTempData('t-sys-config-cate-form-ok', true);  // 操作成功刷新表格
                // 关闭当前iframe弹出层
                admin.closeThisDialog();
            })
            return false;
        });
        // 渲染表格
        table.render({
            elem: '#configTable',
            url: '<?=\yii\helpers\Url::to(["sys-config/index"])?>',
            // toolbar: '#sys-config-cate-table-tool-bar',
            method:'get',
            limit:10,
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field:'id', align: 'center', title:'主键', sort: true, edit:'text'},
                {field:'title', align: 'center', title:'配置标题', sort: true, edit:'text'},
                {field:'name', align: 'center', title:'配置标识', sort: true, edit:'text'},
                {field:'status', align: 'center', title:'状态[0:禁用;1启用]', sort: true, edit:'text'},
                {field:'type', align: 'center', title:'配置类型', sort: true, edit:'text'},
                {field:'cate_id', align: 'center', title:'配置分类', sort: true, edit:'text'},
                {field:'extra', align: 'center', title:'配置值', sort: true, edit:'text'},
                {field:'remark', align: 'center', title:'配置说明', sort: true, edit:'text'},
                {field:'is_hide_remark', align: 'center', title:'是否隐藏说明', sort: true, edit:'text'},
                {field:'default_value', align: 'center', title:'默认配置', sort: true, edit:'text'},
                {field:'sort', align: 'center', title:'排序', sort: true, edit:'text'},
                {field:'create_time', align: 'center', title:'创建时间', sort: true, edit:'text'},
                {field:'update_time', align: 'center', title:'修改时间', sort: true, edit:'text'},
                {fixed: 'right',align: 'center', toolbar: '#sys-config-table-bar', title: '操作', minWidth: 170}
            ]]
        });
        laydate.render({elem: '#sys-config-cate-create_time'});
        laydate.render({elem: '#sys-config-cate-update_time'});
    });
</script>
