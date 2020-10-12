<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel  */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单列表';
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
                <div class="layui-tab layui-tab-brief" lay-filter="filterTab">
                    <ul class="layui-tab-title">
                        <?php $i=0; foreach ($systems as $s) {?>
                        <li <?php if($i==0) {echo 'class="layui-this"'; }?> parent="<?=$s['id'] ?>"><?=$s['name'] ?></li>
                        <?php $i++;} ?>
                        <a class="layui-btn layui-btn-sm pull-right" id="auth-menu-refresh"><i class="layui-icon layui-icon-refresh"></i></a>
                        <a class="layui-btn layui-btn-sm pull-right" style="margin-right: 10px;" id="auth-menu-create"><i class="layui-icon layui-icon-add-1"></i></a>
                    </ul>
                    <div class="layui-tab-content">
                        <table class="layui-table" id="auth-menu-table" lay-filter="auth-menu-table"></table>
                    </div>
                </div>

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
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="details">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 表头操作 -->
<script type="text/html" id="auth-menu-table-tool-bar">
    <a class="layui-btn layui-btn-sm" lay-event="create"><i class="layui-icon layui-icon-add-1"></i></a>
    <a class="layui-btn layui-btn-sm" lay-event="refresh"><i class="layui-icon layui-icon-refresh"></i></a>
</script>
<script>
    layui.use(['layer', 'element','form', 'table','treeTable', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.treeTable;
        var admin = layui.admin;
        var laydate = layui.laydate;
        var _csrf = "<?= Yii::$app->request->csrfToken ?>";
        var element  = layui.element;
        var parent = 1
        var tableId = "";

        // 渲染表格
        var tableObj = table.render({
            elem: '#auth-menu-table',
            tree: {
                iconIndex: 0,  // 折叠图标显示在第几列
                idName: 'id',  // 自定义id字段的名称
                pidName: 'parent',  // 自定义标识是否还有子节点的字段名称
                haveChildName: 'haveChild',  // 自定义标识是否还有子节点的字段名称
                isPidData: true  // 是否是pid形式数据
            },
            // toolbar: '#auth-menu-table-tool-bar',
            // defaultToolbar: ['filter'],
            cols: [
                {field:'name', align: 'center', title:'名称', edit:'text'},
                {field:'route', align: 'center', title:'路由',  edit:'text', width:200},
                {field:'order', align: 'center', title:'权重', edit:'text', width:60},
                {field:'data', align: 'center', title:'Data', edit:'text'},
                {field:'icon', align: 'center', title:'图标', edit:'text'},
                {fixed: 'right',align: 'center', toolbar: '#auth-menu-table-bar', title: '操作', width: 170}
            ],
            reqData: function(data, callback) {
                // 在这里写ajax请求，通过callback方法回调数据
                $.get('<?=Url::to(["index"])?>?parent='+parent, function (res) {
                    callback(res.data);  // 参数是数组类型
                });
            }
        });
        //监听tab切换
        element.on('tab(filterTab)', function(data){
            parent = $(this).attr('parent')
            tableObj.refresh()
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

        $("#auth-menu-create").click(function() {
            showEditModel();
        })

        $("#auth-menu-refresh").click(function() {
            tableObj.refresh(parent)
        })

        $(".layui-tab-close").click(function() {
            return false
        })

        //编辑单元格
        table.on('edit(auth-menu-table)', function(obj) {
            let params = {
                value:obj.value,
                attribute:obj.field,
                '<?=Yii::$app->request->csrfParam?>':'<?=Yii::$app->request->getCsrfToken()?>'
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
                title: data ? '修改菜单' : '添加菜单',
                maxmin: true,
                resize: true,
                area: ['50%', '70%'],
                content: data ? '<?=Url::to(['update'])?>?id='+data.id : '<?= Url::to(['create'])?>?parent='+parent,
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

