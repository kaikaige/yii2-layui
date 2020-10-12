<?php
namespace kaikaige\layui\components;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @var ActiveRecord
     */
    public $model;

    public $fields = [];

    public $fieldClass = 'kaikaige\layui\components\ActiveField';

    public $fieldConfig = [
        'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}</div></div>'
    ];

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->options = ArrayHelper::merge([
            'id' => $this->model->formName().'Form',
            'lay-filter' => $this->model->formName().'Form',
            'class' => 'layui-form model-form layui-row'
        ], $this->options);
    }

    public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content = ob_get_clean();
        $html = Html::beginForm($this->action, $this->method, $this->options);
        $html .= $content;

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        $html .= Html::endForm();
        return $html;
    }

    public function field($model, $attribute, $options = [])
    {
        $field = parent::field($model, $attribute, $options); // TODO: Change the autogenerated stub
        $this->fields[] = $field;
        return $field;
    }

    public function registerClientScript()
    {
    }

}