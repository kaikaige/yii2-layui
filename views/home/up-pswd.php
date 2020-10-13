    <?php
    $form = \yii\widgets\ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => [
            'class' => 'layui-form model-form',
            'lay-filter' => 'user-admin-filter',
            'id' => 'form-psw',
        ],
    ])
    ?>
    <div class="layui-form-item">
        <label class="layui-form-label">原始密码:</label>
        <div class="layui-input-block">
            <input type="password" name="oldPassword" placeholder="请输入原始密码" class="layui-input"
                   lay-verType="tips"/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">新密码:</label>
        <div class="layui-input-block">
            <input type="password" name="newPassword" placeholder="请输入新密码" class="layui-input"
                   lay-verType="tips"/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码:</label>
        <div class="layui-input-block">
            <input type="password" name="rePassword" placeholder="请再次输入新密码" class="layui-input"
                   lay-verType="tips"/>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block text-right">
            <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            <button class="layui-btn" lay-filter="submit-psw" lay-submit>保存</button>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end() ?>
<script>
    layui.use(['layer', 'form', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var admin = layui.admin;

        admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 监听提交
        form.on('submit(submit-psw)', function (data) {
            $.post('', data.field, function () {
                top.layer.msg('修改成功', {icon: 1});
                admin.closeThisDialog();
            })
            return false
        })
    })
</script>