<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel  */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '配置列表';
$this->params['breadcrumbs'][] = $this->title;
?>
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
            <table id="sys-config-table" lay-filter="sys-config-table"></table>
        </div>
        <button class="layui-btn layui-btn-sm icon-btn" id="create-sys-config"
                style="position: absolute; left: 20px;top: 60px;padding: 0 5px;" type="button">
            <i class="layui-icon">&#xe654;</i>添加
        </button>
    </div>
    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- 表格状态列 -->
<script type="text/html" id="table-status">
    <input type="checkbox" lay-filter="ckStatus" value="{{d.id}}" lay-skin="switch" lay-text="可用|禁用" {{d.status==1?'checked':''}}/>
</script>
<!-- 表格操作列 -->
<script type="text/html" id="sys-config-table-bar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="details">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['layer', 'form', 'table','tableX', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var tableX = layui.tableX;
        var admin = layui.admin;
        var laydate = layui.laydate;
        var _csrf = "<?= Yii::$app->request->csrfToken ?>";
        var tableId = "";

        // 渲染表格
        tableX.render({
            elem: '#sys-config-table',
            url: '<?=Url::to(["index"])?>',
            method:'get',
            limit:10,
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                {type: 'checkbox', fixed: 'left'},
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

        //监听工具条
        table.on('tool(sys-config-table)', function (obj) {
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值

            if (layEvent === 'edit') { //修改
                showEditModel(data)
            } else if (layEvent === 'del') { //删除
                delModel(data,obj);
            } else if (layEvent === 'details'){
                viewModel(data);
            }
        });

        //监听搜索、添加、刷新
        table.on('toolbar(sys-config-table)', function(obj) {
            var layEvent = obj.event
            if (layEvent === 'create') { //添加
                showEditModel();
            } else if (layEvent === 'search') { //搜索
                //监听搜索
                var searchForm = form.val("searchForm")
                table.reload('sys-config-table', {
                    page: {curr: 1},
                    where: searchForm
                })
            } else if (layEvent === 'refresh'){ //刷新
                table.reload('sys-config-table')
            }
        })

        //编辑单元格
        table.on('edit(sys-config-table)', function(obj) {
            let params = {
                value:obj.value,
                attribute:obj.field,
                //'".\Yii::$app->request->csrfParam."':'".\Yii::$app->request->getCsrfToken()."'
            }
            $.post('<?= Url::to(['update-attribute']) ?>?id='+obj.data.id, params,function(data) {
                layer.msg('修改成功', {icon:1})
            })
        })

        form.on('switch(ckStatus)', function (obj) {
            let params = {
                value:obj.elem.checked ? 1 : 0,
                attribute: 'status',
            }
            $.post('<?= Url::to(['update-attribute']) ?>?id='+obj.value, params,function(data) {
                layer.msg('修改成功', {icon:1})
            })
        })

        function delModel(table_data,obj) {
            layer.confirm('真的要删除该记录吗？', function(index){
                obj.del(); //删除对应行（tr）的DOM结构
                layer.close(index);
                //向服务端发送删除指令
                var url = "<?= Url::to(['view'])?>?id="+table_data.id;
                $.post(url,{'_csrf-backend':_csrf},function (data) {
                        layer.msg("删除成功！");})
                table.reload('sys-config-table');
            });

        }

        $("#create-sys-config").click(function () {
            showEditModel()
        })

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-sys-config-form-ok', false);
            layer.open({
                type: 2,
                title: data ? '修改配置' : '添加配置',
                maxmin: true,
                resize: true,
                offset: 'rt',
                area: ['70%', '90%'],
                content: data ? '<?=Url::to(['update'])?>?id='+data.id : '<?= Url::to(['create'])?>',
                end: function () {
                    admin.getTempData('t-sys-config-form-ok') && table.reload('sys-config-table');  // 成功刷新表格
                }
            });
        }

        function viewModel(data) {
            top.layui.admin.open({
                type:2,
                title:'查看',
                content:'<?= Url::to(['view'])?>?id='+data.id,
                maxmin: true,
                resize: true,
                area: ['50%', '70%'],
                btn: ['关闭']

            });
        }
        });
</script>

