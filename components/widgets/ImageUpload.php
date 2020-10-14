<?php
namespace kaikaige\layui\components\widgets;

use kaikaige\layui\components\Configs;
use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * <?= \kaikaige\layui\components\widgets\ImageUpload::widget([
    'model' => $model,
    'attribute' => 'name',
    'options' => [
        'size' => 10,//10kb
        'exts' => 'gif|png'
    ]
 * ]) ?>
 * @property ActiveRecord $model
 * @property string $attribute
 * @property string $action 上传路径
 * @property string $preUrl 访问路径OSS地址|图片服务器地址等
 * @property array $options 参考 https://www.layui.com/doc/modules/upload.html#options
 */
class ImageUpload extends Widget
{
    public $model;

    public $attribute;

    public $action;

    public $notice = '';

    public $preUrl;

    public $options = [];

    private $_preId;
    private $_inputId;

    public function init()
    {
        parent::init();
        $this->_preId = $this->getId().'-pre';
        $this->_inputId = Html::getInputId($this->model, $this->attribute);
        if (!$this->notice) {
            if (isset($this->options['exts'])) {
                $this->notice .=  '上传类型为'.$this->options['exts'];
            }
            if (isset($this->options['size'])) {
                $this->notice .=  '不允许大于'.$this->options['size'].'KB';
            }
        }
        $this->action = $this->action ?? Configs::instance()->uploadAction;
        $this->preUrl = $this->preUrl ?? Configs::instance()->preUrl;
        $this->registerJs();
    }

    public function run()
    {
        $label = $this->model->getAttributeLabel($this->attribute);
        $id = $this->getId();
        $src = $this->model->isNewRecord ? '' : $this->preUrl.$this->model->{$this->attribute};
$html=<<<EOF
    <div style="text-align: left">
        <button type="button" class="layui-btn" id="{$this->getId()}">
            <i class="layui-icon">&#xe67c;</i>上传图片
        </button>
        <img class="layui-upload-img img_css" id="{$this->_preId}" src="$src" style="cursor:pointer">
        <span class="text-danger">{$this->notice}</span>
    </div>
EOF;
        $html .= Html::activeHiddenInput($this->model, $this->attribute);
        echo $html;
    }

    private function registerJs() {
        $options = Json::htmlEncode(ArrayHelper::merge([
            'url' => $this->action,
            'auto' => false,
        ], $this->options));
$js=<<<EOF
layui.use(['layer', 'form','upload'], function () {
        var $ = layui.jquery;
        var upload = layui.upload //这里增加upload
        var options = {
            elem: '#{$this->getId()}',
            choose: function (obj) {  //上传前选择回调方法
                top.layer.load();
                obj.preview(function (index, file, result) {
                    var img = new Image();
                    img.src = result;
                    img.onload = function () { //初始化夹在完成后获取上传图片宽高，判断限制上传图片的大小。
                        obj.upload(index, file);
                    };
                });
            },
            done: function (res) {
                top.layer.closeAll('loading');
                // 上传完毕回调
                if (res.code == 200) {
                    top.layer.msg('上传成功！', {icon: 1});
                    $('#{$this->_inputId}').attr('value', res.location);
                    $('#{$this->_preId}').attr('src', '{$this->preUrl}' + res.location);
                    $('#{$this->_preId}').show()
                } else {
                    top.layer.msg(res.mes, {icon: 2});
                }
            },
            error: function () {
                top.layer.closeAll('loading');
                // 请求异常回调
                top.layer.msg('上传图片接口异常！', {icon: 2});
            }
        }
        options = $.extend(options, {$options})
        upload.render(options);
        $('#{$this->_preId}').on('click', function () {
            layer.photos({
                photos: { "data": [{"src": '/'+$('#{$this->_inputId}').val()}] },
                shadeClose: false,
                closeBtn: 2,
                anim: 0
            });
        })
    })
EOF;
        $this->view->registerJs($js);
        if ($this->model->isNewRecord) {
            $this->view->registerCss('.img_css{height:100px;width:100px;display:none;}');
        } else {
            $this->view->registerCss('.img_css{height:100px;width:100px;}');
        }

    }
}
