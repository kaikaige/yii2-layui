<?php
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
    <title>EasyWeb管理系统模板</title>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body class="layui-layout-body">
<?php $this->beginBody() ?>
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            <img src="<?=$baseUrl?>/images/logo.png"/>
            <cite>&nbsp;EasyWeb Iframe</cite>
        </div>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
            </li>
            <?php foreach ($menus as $key=>$menu) { ?>
                <li class="layui-nav-item layui-hide-xs <?php if($key==0){ echo 'layui-this'; } ?>" lay-unselect><a nav-bind="xt<?=$key?>"><?=$menu['name']?></a></li>
            <?php } ?>
            <!-- 小屏幕下变为下拉形式 -->
            <li class="layui-nav-item layui-hide-sm layui-show-xs-inline-block" lay-unselect>
                <a>更多</a>
                <dl class="layui-nav-child">
                    <?php foreach ($menus as $key=>$menu) { ?>
                        <dd lay-unselect><a nav-bind="xt<?=$key?>"><?=$menu['name']?></a></dd>
                    <?php } ?>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="message" title="消息">
                    <i class="layui-icon layui-icon-notice"></i>
                    <span class="layui-badge-dot"></span><!--小红点-->
                </a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="note" title="便签"><i class="layui-icon layui-icon-note"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-screen-full"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a>
                    <img src="<?=$baseUrl?>/images/head.png" class="layui-nav-img">
                    <cite>管理员</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd lay-unselect>
                        <a ew-href="page/template/user-info.html">个人中心</a>
                    </dd>
                    <dd lay-unselect>
                        <a ew-event="psw">修改密码</a>
                    </dd>
                    <hr>
                    <dd lay-unselect>
                        <a ew-event="logout" data-url="page/template/login.html">退出</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="theme" data-url="<?= \yii\helpers\Url::to(['theme']) ?>" title="主题"><i class="layui-icon layui-icon-more-vertical"></i></a>
            </li>
        </ul>
    </div>

    <!-- 侧边栏 -->
    <div class="layui-side">
        <div class="layui-side-scroll">
            <!-- 系统一的菜单 -->
            <?php foreach ($menus as $key=>$sys) { ?>
                <ul class="layui-nav layui-nav-tree <?php if($key!=0){ echo ' layui-hide'; } ?>" nav-id="xt<?=$key?>" lay-filter="admin-side-nav" style="margin: 15px 0;">
                    <?php foreach($sys['items'] as $menu){?>
                    <li class="layui-nav-item">
                        <a title="<?= \yii\helpers\Url::to([$menu['route']]) ?>" <?php if($menu['route'] && empty($menu['items'])) {echo 'lay-href="'.\yii\helpers\Url::to([$menu['route']]).'"';}?>><i class="layui-icon layui-icon-home"></i>&emsp;<cite><?=$menu['name']?></cite></a>
                        <?php if(!empty($menu['items'])) {?>
                        <dl class="layui-nav-child">
                            <?php foreach ($menu['items'] as $btn) {?>
                            <dd><a title="<?= \yii\helpers\Url::to([$btn['route']]) ?>" lay-href="<?= \yii\helpers\Url::to([$btn['route']]) ?>"><?=$btn['name'] ?></a></dd>
                            <?php } ?>
                        </dl>
                        <?php }?>
                    </li>
                    <?php }?>
                </ul>
            <?php } ?>
        </div>
    </div>

    <!-- 主体部分 -->
    <div class="layui-body"></div>
    <!-- 底部 -->
    <div class="layui-footer">Copyright © 2019 EasyWeb All rights reserved.</div>
</div>

<!-- 加载动画 -->
<script>
    /** EasyWeb iframe v3.1.5 date:2019-10-05 License By http://easyweb.vip */
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
        iconPicker: 'iconPicker/iconPicker'
    }).use(['layer', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var admin = layui.admin;
        console.log(admin)
        // 移除loading动画
        setTimeout(function () {
            admin.removeLoading();
        }, window == top ? 600 : 100);

    });

    // 获取当前项目的根路径，通过获取layui.js全路径截取assets之前的地址
    function getProjectUrl() {
        var layuiDir = layui.cache.dir;
        if (!layuiDir) {
            var js = document.scripts, last = js.length - 1, src;
            for (var i = last; i > 0; i--) {
                if (js[i].readyState === 'interactive') {
                    src = js[i].src;
                    break;
                }
            }
            var jsPath = src || js[last].src;
            layuiDir = jsPath.substring(0, jsPath.lastIndexOf('/') + 1);
        }
        return layuiDir.substring(0, layuiDir.indexOf('assets'));
    }

    layui.use(['index'], function () {
        var $ = layui.jquery;
        var index = layui.index;
        // 默认加载主页
        index.loadHome({
            menuPath: '<?= $defaultUrl ?>',
            menuName: '<i class="layui-icon layui-icon-home"></i>',
            loadSetting: false
        });

    });
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>