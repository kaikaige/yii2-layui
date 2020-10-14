<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \kaikaige\layui\gii\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
$tableName = Inflector::camel2id(StringHelper::basename($generator->modelClass));
echo "<?php\n";
?>

use yii\helpers\Html;
use kaikaige\layui\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form kaikaige\layui\components\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'options'=>[
            'lay-filter'=>'user-admin-filter',
            'id'=>'<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form'
        ],
    ]); ?>

<?php
    $formFields = empty($generator->formFields) ? $generator->getColumnNames() :$generator->formFields;
    foreach ($formFields as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
<script>
    layui.use(['layer', 'form', 'admin', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form').attr('action');
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            $.post(url, data.field, function() {
                top.layer.msg('添加成功', {icon: 1});
                admin.putTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok', true);  // 操作成功刷新表格
                // 关闭当前iframe弹出层
                admin.closeThisDialog();
            })
            return false;
        });
<?php foreach ($generator->dateFields as $val ){ ?>
        laydate.render({elem: '#<?= str_replace('-', '',$tableName).'-'.$val?>'});
<?php } ?>
    });
</script>
