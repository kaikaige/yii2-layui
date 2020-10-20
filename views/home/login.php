<?php
use kaikaige\layui\components\Configs;

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
    <title>登录系统</title>
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
        <img src="<?=$baseUrl?>/images/logo.png"> <?= Configs::instance()->title ?>
    </div>
    <div class="login-body">
        <div class="layui-card">
            <div class="layui-card-header">
                <i class="layui-icon layui-icon-engine"></i>&nbsp;&nbsp;用户登录
            </div>
            <?php $form = \kaikaige\layui\components\ActiveForm::begin([
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
                <?= $form->field($model, 'username')->textInput(['style'=>['width'=>'100%']])->label('<i class="layui-icon layui-icon-username"></i>') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('<i class="layui-icon layui-icon-password"></i>') ?>
                <div class="layui-row">
                    <div class="layui-col-md8">
                        &nbsp;
                    </div>
                    <div class="layui-col-md4">
                        <input type="checkbox" name="LoginForm[rememberMe]" title="记住密码" lay-skin="primary" value="1">
                    </div>
                </div>

            <?php /*
<!--                <?//= $form->field($model, 'captcha', ['template'=>'<div class="layui-form-item">
//                    {input}
//                </div>'])->widget(\yii\captcha\Captcha::classname(), [
//                    'captchaAction' => ['home/captcha'],
//                    'template' => '<div class="layui-form-item">
//                    <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
//                    <div class="layui-input-block">
//                        <div class="layui-row inline-block">
//                            <div class="layui-col-xs7">
//                            {input}
//                            </div>
//                            <div class="layui-col-xs5" style="padding-left: 6px;">
//                                {image}
//                            </div>
//                        </div>
//                    </div>
//                </div>'
//                ])->label(null) ?>
 */ ?>

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
            layer.load(0, {shade: false, time: 10*1000});
            $.post("", obj.field, function (res) {
                layer.closeAll('loading');
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