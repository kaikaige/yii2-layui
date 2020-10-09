<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel  */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统配置列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-menu-index">
    <!-- 关闭Tab时顶部标题 -->
    <div class="layui-body-header">
        <span class="layui-body-header-title"><?= Html::encode($this->title) ?></span>
        <span class="layui-breadcrumb pull-right">
            <a href="<?= Url::to("/index") ?>">首页</a>
            <a><cite><?= Html::encode($this->title) ?></cite></a>
        </span>
    </div>

    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <div class="layui-form toolbar">
                    <div class="layui-form-item">
                        <?php  $form = ActiveForm::begin([
                            'options'=>['style'=>'display:inline;', 'class'=>'layui-form', 'lay-filter'=>'searchForm'],
                            'fieldConfig'=>[
                                'template' => '<div class="layui-inline">{label}<div class="layui-input-inline">{input}</div></div>',
                                'labelOptions' => ['class' => 'layui-form-label'],
                                'inputOptions' => ['class'=>'layui-input'],
                                'options'=>['tag'=>false]
                            ]
                        ])?>

                        <?php  ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="layui-table" id="auth-menu-table" lay-filter="auth-menu-table"></table>
            </div>
        </div>

    </div>
</div>
<!-- 表格状态列 -->
<script type="text/html" id="table-status">
    <input type="checkbox" lay-filter="ckStatus" value="{{d.id}}" lay-skin="switch" lay-text="可用|禁用" {{d.status==1?'checked':''}}/>
</script>
<!-- 表格操作列 -->
<script type="text/html" id="auth-menu-table-bar">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 表头操作 -->
<script type="text/html" id="auth-menu-table-tool-bar">
    <a class="layui-btn layui-btn-sm" lay-event="create"><i class="layui-icon layui-icon-add-1"></i></a>
    <a class="layui-btn layui-btn-sm" lay-event="refresh"><i class="layui-icon layui-icon-refresh"></i></a>
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
            elem: '#auth-menu-table',
            url: '<?=Url::to(["system"])?>',
            toolbar: '#auth-menu-table-tool-bar',
            method:'get',
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: false,
            cellMinWidth: 100,
            cols: [[
                // {type: 'checkbox', fixed: 'left'},
                {field:'name', align: 'center', title:'名称', sort: true, edit:'text'},
                {field:'order', align: 'center', title:'序号', sort: true, edit:'text'},
                // {field:'data', align: 'center', title:'Data', sort: true, edit:'text'},
                // {field:'icon', align: 'center', title:'图标', sort: true, edit:'text'},
                {fixed: 'right',align: 'center', toolbar: '#auth-menu-table-bar', title: '操作', minWidth: 170},

            ]]
        });

        //监听工具条
        table.on('tool(auth-menu-table)', function (obj) {
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
        table.on('toolbar(auth-menu-table)', function(obj) {
            var layEvent = obj.event
            if (layEvent === 'create') { //添加
                showEditModel();
            } else if (layEvent === 'search') { //搜索
                //监听搜索
                var searchForm = form.val("searchForm")
                table.reload('auth-menu-table', {
                    page: {curr: 1},
                    where: searchForm
                })
            } else if (layEvent === 'refresh'){ //刷新
                table.reload('auth-menu-table')
            }
        })

        //编辑单元格
        table.on('edit(auth-menu-table)', function(obj) {
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
            layer.confirm('删除该记录，对应的菜单会同步删除，确定删除吗？', function(index){
                obj.del(); //删除对应行（tr）的DOM结构
                layer.close(index);
                //向服务端发送删除指令
                var url = "<?= Url::to(['delete'])?>?id="+table_data.id;
                $.post(url,{'_csrf-backend':_csrf},function (data) {
                    layer.msg("删除成功！");})
                table.reload('auth-menu-table');
            });

        }

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-auth-menu-form-ok', false);

            top.layui.admin.open({
                type: 2,
                title: data ? '修改系统配置' : '添加系统配置',
                maxmin: true,
                resize: true,
                area: ['50%', '70%'],
                content: data ? '<?=Url::to(['update'])?>?id='+data.id : '<?= Url::to(['create', 'type'=>'system'])?>',
                end: function () {
                    admin.getTempData('t-auth-menu-form-ok') && table.reload('auth-menu-table');  // 成功刷新表格
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
