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
<div class="sys-config-cate-index">
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

            <?= $form->field($searchModel, 'title', ['inputOptions'=>['placeholder'=>'请输入标题']]) ?>
        <?= $form->field($searchModel, 'status')->dropDownList([''=>'请选择状态'] + [0=>'禁用', 1=>'可用']) ?>
			
    <?php  ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="layui-table" id="sys-config-cate-table" lay-filter="sys-config-cate-table"></table>
            </div>
        </div>

    </div>
</div>
<!-- 表格状态列 -->
<script type="text/html" id="table-status">
    <input type="checkbox" lay-filter="ckStatus" value="{{d.id}}" lay-skin="switch" lay-text="可用|禁用" {{d.status==1?'checked':''}}/>
</script>
<!-- 表格操作列 -->
<script type="text/html" id="sys-config-cate-table-bar">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 表头操作 -->
<script type="text/html" id="sys-config-cate-table-tool-bar">
    <a class="layui-btn layui-btn-sm" lay-event="create"><i class="layui-icon layui-icon-add-1"></i></a>
    <a class="layui-btn layui-btn-sm" lay-event="search"><i class="layui-icon layui-icon-search"></i></a>
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
            elem: '#sys-config-cate-table',
            url: '<?=Url::to(["index"])?>',
            toolbar: '#sys-config-cate-table-tool-bar',
            method:'get',
            limit:10,
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field:'id', align: 'center', title:'主键', sort: true, edit:'text'},
                {field:'title', align: 'center', title:'标题', sort: true, edit:'text'},
                {field:'sort', align: 'center', title:'排序', sort: true, edit:'text'},
                {field:'status', align: 'center', title:'状态', sort: true, templet:'#table-status'},
                {fixed: 'right',align: 'center', toolbar: '#sys-config-cate-table-bar', title: '操作', minWidth: 190}
            ]]
        });

        //监听工具条
        table.on('tool(sys-config-cate-table)', function (obj) {
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
        table.on('toolbar(sys-config-cate-table)', function(obj) {
            var layEvent = obj.event
            if (layEvent === 'create') { //添加
                showEditModel();
            } else if (layEvent === 'search') { //搜索
                //监听搜索
                var searchForm = form.val("searchForm")
                table.reload('sys-config-cate-table', {
                    page: {curr: 1},
                    where: searchForm
                })
            } else if (layEvent === 'refresh'){ //刷新
                table.reload('sys-config-cate-table')
            }
        })

        //编辑单元格
        table.on('edit(sys-config-cate-table)', function(obj) {
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
                table.reload('sys-config-cate-table');
            });

        }

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-sys-config-cate-form-ok', false);

            top.layui.admin.open({
                type: 2,
                title: data ? '修改系统配置' : '添加系统配置',
                maxmin: true,
                resize: true,
                area: ['80%', '80%'],
                content: data ? '<?=Url::to(['sys-config/index'])?>?id='+data.id : '<?= Url::to(['sys-config/index'])?>',
                end: function () {
                    admin.getTempData('t-sys-config-cate-form-ok') && table.reload('sys-config-cate-table');  // 成功刷新表格
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

