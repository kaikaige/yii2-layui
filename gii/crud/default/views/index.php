<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \kaikaige\layui\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$index_arr[] = "{type: 'checkbox', fixed: 'left'}";
$tableSchema = $generator->getTableSchema();
$tableName = Inflector::camel2id(StringHelper::basename($generator->modelClass));
$tableId = $tableName.'-table';
/* @var $modelClass \yii\db\ActiveRecord */
$modelClass = new $generator->modelClass;
$listFields = empty($generator->listFields) ? $generator->getColumnNames() :$generator->listFields;
$primary_key = $generator->getPrimayKey()[0];
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
<?= "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n"?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString($generator->title.'列表') ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <!-- 关闭Tab时顶部标题 -->
    <div class="layui-body-header">
        <span class="layui-body-header-title"><?="<?="?> Html::encode($this->title) ?></span>
        <span class="layui-breadcrumb pull-right">
            <a href="<?="<?="?> Url::to("/index") ?>">首页</a>
            <a><cite><?="<?="?> Html::encode($this->title) ?></cite></a>
        </span>
    </div>

    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <div class="layui-form toolbar">
                    <div class="layui-form-item">
    <?= "<?php " ?> $form = ActiveForm::begin([
        'options'=>['style'=>'display:inline;', 'class'=>'layui-form search-form', 'lay-filter'=>'searchForm'],
        'fieldConfig'=>[
            'template' => '<div class="layui-inline">{label}<div class="layui-input-inline">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false]
        ]
    ])?>

    <?= $generator->generateSearchField();?>
    <?= "<?php " ?> ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="layui-table" id="<?= $tableId ?>" lay-filter="<?= $tableId ?>"></table>
            </div>
        </div>

    </div>
</div>
<!-- 表格状态列 -->
<script type="text/html" id="table-status">
    <input type="checkbox" lay-filter="ckStatus" value="{{d.id}}" lay-skin="switch" lay-text="可用|禁用" {{d.is_deleted==0?'checked':''}}/>
</script>
<!-- 表格操作列 -->
<script type="text/html" id="<?= $tableName ?>-table-bar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="details">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 表头操作 -->
<script type="text/html" id="<?= $tableName ?>-table-tool-bar">
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
        var _csrf = "<?="<?="?> Yii::$app->request->csrfToken ?>";
        var tableId = "";

        // 渲染表格
        tableX.render({
            elem: '#<?= $tableId ?>',
            url: '<?= "<?=" ?>Url::to(["index"])?>',
            toolbar: '#<?= $tableName ?>-table-tool-bar',
            method:'get',
            limit:20,
            defaultToolbar: ['filter'],
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                {type: 'checkbox', fixed: 'left'},
<?php foreach ($listFields as $attribute) {echo "\t\t\t\t".$generator->generateListField($attribute)."\n";}?>
                {fixed: 'right',align: 'center', toolbar: '#kx-goods-table-bar', title: '操作', minWidth: 170}
            ]]
        });

        //监听工具条
        table.on('tool(<?= $tableId ?>)', function (obj) {
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
        table.on('toolbar(<?= $tableId ?>)', function(obj) {
            var layEvent = obj.event
            if (layEvent === 'create') { //添加
                showEditModel();
            } else if (layEvent === 'search') { //搜索
                //监听搜索
                search()
            } else if (layEvent === 'refresh'){ //刷新
                table.reload('<?= $tableId ?>')
            }
        })

        $(".search-form input").keyup(function(event){
            if(event.keyCode ==13){
                search()
            }
        });

        function search() {
            var searchForm = form.val("searchForm")
            table.reload('<?= $tableId ?>', {
                page: {curr: 1},
                where: searchForm
            })
        }

        //编辑单元格
        table.on('edit(<?= $tableId ?>)', function(obj) {
            let params = {
                value:obj.value,
                attribute:obj.field,
                //'".\Yii::$app->request->csrfParam."':'".\Yii::$app->request->getCsrfToken()."'
            }
            $.post('<?= "<?=" ?> Url::to(['update-attribute']) ?>?id='+obj.data.id, params,function(data) {
                layer.msg('修改成功', {icon:1})
            })
        })

        form.on('switch(ckStatus)', function (obj) {
            let params = {
                value:obj.elem.checked ? 0 : 1,
                attribute: 'is_deleted',
            }
            $.post('<?= "<?=" ?> Url::to(['update-attribute']) ?>?id='+obj.value, params,function(data) {
                layer.msg('修改成功', {icon:1})
            })
        })

        function delModel(table_data,obj) {
            layer.confirm('真的要删除该记录吗？', function(index){
                obj.del(); //删除对应行（tr）的DOM结构
                layer.close(index);
                //向服务端发送删除指令
                var url = "<?="<?="?> Url::to(['delete'])?>?id="+table_data.<?=$primary_key?>;
                $.post(url,{'_csrf-backend':_csrf},function (data) {
                        layer.msg("删除成功！");})
                table.reload('<?= $tableId ?>');
            });

        }

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok', false);

            top.layui.admin.open({
                type: 2,
                title: data ? '修改<?=$generator->title?>' : '添加<?=$generator->title?>',
                maxmin: true,
                resize: true,
                area: ['50%', '70%'],
                content: data ? '<?="<?="?>Url::to(['update'])?>?id='+data.<?=$primary_key?> : '<?="<?="?> Url::to(['create'])?>',
                end: function () {
                    admin.getTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok') && table.reload('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table');  // 成功刷新表格
                }
            });
        }

        function viewModel(data) {
            top.layui.admin.open({
                type:2,
                title:'查看',
                content:'<?="<?="?> Url::to(['view'])?>?id='+data.<?=$primary_key?>,
                maxmin: true,
                resize: true,
                area: ['50%', '70%'],
                btn: ['关闭']

            });
        }
        <?php $generator->registerJs() ?>
});
</script>

