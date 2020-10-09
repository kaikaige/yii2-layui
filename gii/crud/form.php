<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator app\components\gii\generators\crud\Generator */
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'title');
echo $form->field($generator, 'searchModelClass');
if ($_POST) {
    $table_s = $generator->getTableSchema();
    $columns = $table_s->columns;
    $cols = [];
    foreach ($columns as $key=>$val) $cols[$key]=$val->name;
    echo $form->field($generator, 'listFields')->checkboxList($cols);
    echo $form->field($generator, 'searchFields')->checkboxList($cols);
    echo $form->field($generator, 'formFields')->checkboxList($cols);
// 	echo $form->field($generator, 'inputType')->hiddenInput();
    if (empty($generator->inputType)) {
        foreach ($columns as $name=>$val) $generator->inputType[$name] = 1;
    }
    echo "<div form-group field-generator-inputType'>";
    echo '<label control-label help" data-original-title title>字段类型</label>';
    echo "<div  class='row'>";
    foreach ($columns as $name=>$val) {
        echo '<div class="col-lg-3"><label control-label">'.$name.'</label></div>';

        echo '<div class="col-lg-3">'. Html::dropDownList("Generator[inputType][$name]", $generator->inputType[$name], $generator->fieldTypes(), ['class'=>'form-control']).'</div>';
    }
    echo "</div></div>";
}
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
//echo $form->field($generator, 'indexWidgetType')->dropDownList([
//    'grid' => 'GridView',
////    'list' => 'ListView',
//]);
echo $form->field($generator, 'enableI18N')->checkbox();
//echo $form->field($generator, 'isBatch')->checkbox();
//echo $form->field($generator, 'enablePjax')->checkbox();
//echo $form->field($generator, 'messageCategory');
