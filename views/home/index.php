<?php
$asset = \kaikaige\layui\asset\LayuiAsset::register($this);
$baseUrl = $asset->baseUrl;
?>
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
                        <a ew-event="logout" data-url="<?= \yii\helpers\Url::to(['/site/logout']) ?>">退出</a>
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
</div>
<!-- 加载动画 -->
<?php
$js = <<<EOD
layui.use(['index'], function () {
        var $ = layui.jquery;
        var index = layui.index;
        // 默认加载主页
        index.loadHome({
            menuPath: '{$defaultUrl}',
            menuName: '<i class="layui-icon layui-icon-home"></i>',
            loadSetting: false
        });

    });
EOD;
$this->registerJs($js, \yii\web\View::POS_END);
?>
