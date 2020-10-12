<?php
/** @var $asset */
/** @var $this  \yii\web\View */
$asset = \kaikaige\layui\asset\LayuiAsset::register($this);
$baseUrl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="<?=$baseUrl?>/images/favicon.ico" rel="icon">
    <title><?= $this->title?></title>
    <?php $this->registerCsrfMetaTags() ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<body class="">
<?php $this->beginBody() ?>

<?= $content ?>

<script>
// 以下代码是配置layui扩展模块的目录，每个页面都需要引入
    layui.config({
        version: '315',
        base: '<?=$baseUrl?>' + '/module/'
    }).extend({
        formSelects: 'formSelects/formSelects-v4',
        treetable: 'treetable-lay/treetable',
        dropdown: 'dropdown/dropdown',
        notice: 'notice/notice',
        step: 'step-lay/step',
        dtree: 'dtree/dtree',
        citypicker: 'city-picker/city-picker',
        tableSelect: 'tableSelect/tableSelect',
        Cropper: 'Cropper/Cropper',
        zTree: 'zTree/zTree',
        introJs: 'introJs/introJs',
        fileChoose: 'fileChoose/fileChoose',
        tagsInput: 'tagsInput/tagsInput',
        Drag: 'Drag/Drag',
        CKEDITOR: 'ckeditor/ckeditor',
        Split: 'Split/Split',
        cascader: 'cascader/cascader',
        iconPicker: 'iconPicker/iconPicker',
        treeTable: 'treeTable/treeTable',
    }).use(['layer', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var admin = layui.admin;

        $(document).ajaxStart(function(){
            layer.load(0, {
                shade: false,
                time: 10*1000
            });
        }).ajaxComplete(function() {
            layer.closeAll('loading');
        }).ajaxError(function(event, res) {
            if (res.status == 422) {
                layer.msg(res.responseJSON[0].message, {icon:2})
            } else if (res.status == 400) {
                layer.msg(res.responseJSON.message, {icon:2})
            } else if (res.status == 500) {
                <?php if(YII_ENV_DEV) {
                    echo "layer.msg(res.responseJSON.message, {icon:2})";
                } else {
                    echo "layer.msg('服务器内部错误', {icon:2})";
                }?>
            }
        })
        // 移除loading动画
        setTimeout(function () {
            admin.removeLoading();
        }, window == top ? 600 : 100);

    });
</script>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>