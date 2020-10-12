<?php
/** @var $this \yii\web\View */
$asset = \kaikaige\layui\asset\LayuiAsset::register($this);
$baseUrl = $asset->baseUrl;
$css = <<<EOF
body {
    background-image: url("{$baseUrl}/images/bg_login2.png");
}
EOF;
$this->registerCss($css);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>登录</title>
    <link rel="stylesheet" href="<?=$baseUrl?>/css/login.css?v=315">
    <?php $this->registerCsrfMetaTags() ?>
    <!--[if lt IE 9]>
    <script src="<?=$baseUrl?>/js/html5shiv.min.js"></script>
    <script src="<?=$baseUrl?>/js/respond.min.js"></script>
    <![endif]-->
    <script>
        if (window != top) {
            top.location.replace(location.href);
        }
    </script>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="login-wrapper">
    <div class="login-header">
        <img src="<?=$baseUrl?>/images/logo.png"> MS后台管理系统
    </div>
    <div class="login-body">
        <div class="layui-card">
            <div class="layui-card-header">
                <i class="layui-icon layui-icon-engine"></i>&nbsp;&nbsp;用户登录
            </div>
            <?php $form = \kaikaige\layui\components\ActiveForm::begin([
                    'model' => $model,
                    'fieldConfig' => [
                    'template' => '<div class="layui-form-item">
                    {label}
                    <div class="layui-input-block">
                    {input} 
                    </div>
                </div>'
                ],
                'options' => [
                    'class' => 'layui-card-body layui-form layui-form-pane'
                ]
            ]) ?>
                <?= $form->field($model, 'username')->textInput()->label('<i class="layui-icon layui-icon-username"></i>') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('<i class="layui-icon layui-icon-password"></i>') ?>
<!--                <div class="layui-form-item">-->
<!--                    <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>-->
<!--                    <div class="layui-input-block">-->
<!--                        <div class="layui-row inline-block">-->
<!--                            <div class="layui-col-xs7">-->
<!--                                <input name="code" type="text" placeholder="验证码" class="layui-input"-->
<!--                                       autocomplete="off" lay-verType="tips" lay-verify="required" required/>-->
<!--                            </div>-->
<!--                            <div class="layui-col-xs5" style="padding-left: 6px;">-->
<!--                                <img class="login-captcha" src="https://www.oschina.net/action/user/captcha">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="layui-form-item">-->
<!--                    <a href="javascript:;" class="layui-link">帐号注册</a>-->
<!--                    <a href="javascript:;" class="layui-link pull-right">忘记密码？</a>-->
<!--                </div>-->
                <div class="layui-form-item">
                    <button lay-filter="login-submit" class="layui-btn layui-btn-fluid" lay-submit>登 录</button>
                </div>
<!--                <div class="layui-form-item login-other">-->
<!--                    <label>第三方登录</label>-->
<!--                    <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>-->
<!--                    <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>-->
<!--                    <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>-->
<!--                </div>-->
            <?php \kaikaige\layui\components\ActiveForm::end() ?>
        </div>
    </div>

    <div class="login-footer">
        <p>© 2019 kaikaige 版权所有</p>
<!--        <p>-->
<!--            <span><a href="https://easyweb.vip" target="_blank">获取授权</a></span>-->
<!--            <span><a href="https://easyweb.vip/doc/" target="_blank">开发文档</a></span>-->
<!--            <span><a href="https://demo.easyweb.vip/spa/" target="_blank">单页面版</a></span>-->
<!--        </p>-->
    </div>
</div>
<?php $this->endBody() ?>
<!-- js部分 -->
<script>
    layui.use(['layer', 'form'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;

        form.on('submit(login-submit)', function (obj) {
            $.post("", obj.field, function (res) {
                if(res.code != 200) {
                    layer.msg(res.message, {icon: 2, time: 1000});
                } else {
                    layer.msg(res.message, {icon: 1, time: 1500}, function () {
                        location.replace('')
                    });
                }
            })
            return false;
        });

        // 图形验证码
        // $('.login-captcha').click(function () {
        //     this.src = this.src + '?t=' + (new Date).getTime();
        // });
    });
</script>
</body>
</html>
<?php $this->endPage()?>