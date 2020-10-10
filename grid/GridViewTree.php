<?php
/**
 * Created by PhpStorm.
 * User: gaokai
 * Date: 2019-10-23
 * Time: 14:53
 */

namespace kaikaige\layui\grid;

use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

class GridViewTree extends GridView
{
    public function renderJs() {
        $this->options = ArrayHelper::merge($this->options, [
            'toolbar' => '#'.$this->_toolBarId,
            'elem' => '#'.$this->id,
            'url' => \yii\helpers\Url::to($this->url),
            'cols' =>  [$this->genCol()],
        ]);

        $barEvent = "";
        foreach ($this->rowButtons as $event=>$button) {
            if (!isset($button['js'])) continue;
            $barEvent .= "if (obj.event == '".$event."') {".$button['js']."}";
        }

        $barEvent = str_replace("{{createFormJs}}", $this->createFormJs, $barEvent);
        $toolbarEvent = "";
        foreach ($this->toolbarButtons as $event=>$button) {
            if (!isset($button['js'])) continue;
            $toolbarEvent .= "if (obj.event == '".$event."') {".str_replace("{{id}}", $this->getId(), $button['js'])."}\n";
        }
        $toolbarEvent = str_replace("{{createFormJs}}", $this->createFormJs, $toolbarEvent);
        $options = json_encode($this->options, JSON_UNESCAPED_SLASHES);
        $js = "
        layui.use(".Json::htmlEncode($this->layuiModule).", function(){
          //第一个实例
          var $ = layui.jquery
          ".$this->defLayuiModule()."
          var tableObj = treetable.render(".$options.");
          var searchForm = {}
          var loading
          $(document).ajaxStart(function(){
            loading = layer.load(0, {
                shade: false,
            });
          }).ajaxComplete(function() {
            layer.close(loading);
          }).ajaxError(function(event, res) {
            if (res.status == 422) {
                layer.msg(res.responseJSON[0].message, {icon:2})
            } else if (res.status == 400) {
                layer.msg(res.responseJSON.message, {icon:2})
            }
          })
          table.on('tool($this->id)', function(obj) {
            $barEvent
          })
          table.on('toolbar($this->id)', function(obj) {
            $toolbarEvent
          })
        });";
        $this->view->registerJs($js, View::POS_END);
    }

    protected function defaultToobarButtons()
    {
        return [
            'create' => [
                'title' => '<i class="layui-icon layui-icon-add-1"></i>',
                'options' => [
                    'class' => 'layui-btn layui-btn-sm',
                ],
                'js' => '
                $.get("'.\yii\helpers\Url::to(['create-view']).'", function(data) {
                    var index = layer.open({
                      type: 1,
                      title: "添加",
                      area: setpage(),
                      content: data,
                      maxmin: true,
                      success:function() {
                        '.$this->createFormJs.'
                        openLoad();
                        form.render();
                        form.on("submit('.$this->model->formName().'Submit)", function(data) {
                            $.post("'.\yii\helpers\Url::to(['create']).'", data.field, function(obj) {
                                layer.close(index)
                                layer.msg("添加成功", {icon:1})
                                location.reload()
                            })
                            return false
                        })
                      },
                    })
                }); 
                ',
            ],
        ];
    }
}